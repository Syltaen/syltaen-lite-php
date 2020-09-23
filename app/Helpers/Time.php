<?php

namespace Syltaen;

class Time
{
    /**
     * Set a default timezone for the application
     *
     * @return void
     */
    public static function setDefaultTimezone()
    {
        date_default_timezone_set(App::config("timezone"));
    }


    /**
     * Normalize a value into a timestamp
     *
     * @param mixed $date A timestamp, a string, an array
     * @return void
     */
    public static function normalize($date)
    {
        if (!$date) return false;

        // A timestamp
        if (is_int($date) || (string) intval($date) == $date) return (int) $date;

        // An array of 'year', 'month', 'day'
        if (is_array($date)) return static::fromArray($date);

        // Convert string
        return (int) strtotime($date);
    }


    /**
     * Get a timestamp from a array-date : year, month, day, hour, minute, second
     *
     * @param array $array
     * @return void
     */
    public static function fromArray($array)
    {
        // Add defaults
        $array = array_merge([
            "year"   => date("Y"),
            "month"  => "01",
            "day"    => "01",
            "hour"   => "00",
            "minute" => "00",
            "second" => "00",
        ], $array);

        // Return timestamp of formed date
        return (int) strtotime(
            "$array[year]-$array[month]-$array[day] $array[hour]:$array[minute]:$array[second]"
        );
    }


    /**
     * Get the offset of the theme's timezone at a certain date
     */
    public static function getTimezoneOffset($date = "now")
    {
        $timezone = new \DateTimeZone(App::config("timezone"));
        return $timezone->getOffset(new \DateTime($date, $timezone));
    }


    /**
     * Normalize a date, offset it with the theme's timezone and transform it into a date string
     *
     * @param mixed $date
     * @return string
     */
    public static function normalizedOffsetedString($date)
    {
        if (!$date) return false;
        return date(DATE_ATOM, static::normalize($date) + static::getTimezoneOffset());
    }

    // ==================================================
    // > LISTING
    // ==================================================
    /**
     * Get a list of dates between two dates
     *
     * @param string $start
     * @param string $end
     * @return array
     */
    public static function getListFromSpan($start, $end)
    {
        $dates  = [];
        $time   = strtotime($start);
        $end    = strtotime($end);
        $offset = get_option("gmt_offset") * 3600;

        while ($time <= $end) {
            $dates[] = [
                "time" => $time,
                "date" => date("Y-m-d", $time),
                "text" => ucfirst(date_i18n("l d F Y", $time + $offset))
            ];

            $time += DAY_IN_SECONDS;
        }

        return $dates;
    }

    // ==================================================
    // > FORMATTING
    // ==================================================
    /**
     * Format a time span into a nice text
     *
     * @param int|boolean $start U time
     * @param int|boolean $end U time
     * @return string|bool
     */
    public static function formatSpan($start = false, $end = false)
    {
        // No date to display
        if (empty($start)) return false;

        // No end date : format only the start date
        if (empty($end) || $start == $end) return static::formatMaybeHours($start);

        // Same day, different hours
        if (date("d/m/Y", $start) == date("d/m/Y", $end)) {
            return
                date("d/m/Y", $start)."<br>".
                date("H:i", $start) . " - " . date("H:i", $end);
        }

        // Different days
        return nl2br(sprintf(
            __("Du %s \nau %s", "syltaen"),
            static::formatMaybeHours($start),
            static::formatMaybeHours($end)
        ));
    }

    /**
     * Format a U time and include the hours, if it's different that midnight
     *
     * @return string
     */
    public static function formatMaybeHours($time, $date_only_format = "d/m/Y", $with_hour_format = "d/m/Y H:i")
    {
        // Is midnight
        if (date("H:i", $time) == "00:00") {
            return date($date_only_format, $time);
        }

        return date($with_hour_format, $time);
    }


    /**
     * Format a list of dates into a nice readable text
     *
     * @param $dates in a strtotime format
     * @return string
     */
    public static function formatList($dates)
    {
        $groups = [];
        $offset = get_option("gmt_offset") * 3600;

        // Separate into groups of concecutive days
        foreach ($dates as $i=>$date) {
            // Not date before this one or not the following day, create a new group
            if ($i == 0 || strtotime($dates[$i]) - DAY_IN_SECONDS != strtotime($dates[$i - 1])) {
                $groups[] = [];
            }

            // Add date to the last group
            $groups[count($groups) - 1][] = strtotime($date);
        }

        // Transform each group to a nice format
        $groups = array_map(function ($group) use ($offset) {

            // Group of 1 day
            if (count($group) == 1) {
                return "le " . date_i18n("d F _Y_", $group[0] + $offset);
            }

            // Group of 2 days or more
            $template = count($group) == 2 ? "les %s et %s": "du %s au %s";
            $first    = $group[0];
            $last     = $group[count($group) - 1];

            // Same month & year
            if (date("m Y", $first) == date("m Y", $last)) {
                $first_formated = date("d", $first);
            }
            // Different month, same year
            else if (date("Y", $first) == date("Y", $last)) {
                $first_formated = date_i18n("d F", $first + $offset);
            }
            // Different month && same year
            else {
                $first_formated = date_i18n("d F _Y_", $first + $offset);
            }

            return sprintf($template, $first_formated, date_i18n("d F _Y_", $last + $offset));
        }, $groups);

        // Join all groups in a single string
        $last_group = array_pop($groups);
        $string = count($groups) > 0 ? implode(", ", $groups) . " et " . $last_group : $last_group;

        // Transform 01 to 1er
        $string = str_replace(" 01 ", " 1er ", $string);

        // Keep only the last iteration of each year
        $seen_years = [];
        $string = strrev(preg_replace_callback("/_([0-9]{4})_ /", function ($match) use (&$seen_years) {
            if (in_array($match[1], $seen_years)) return "";
            $seen_years[] = $match[1];
            return $match[1] . " ";
        }, strrev($string)));


        return ucfirst($string) . ".";
    }
}