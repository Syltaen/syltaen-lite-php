import Barba from "barba.js"
import $ from "jquery"
# import "hammerjs"
import "jquery.transit"

import "./../tools/jquery.addClassAt.coffee"

$roots = $("html, body")

# ==================================================
# > SCROLL TOP
# ==================================================
$ ->
    $("#scroll-top").addClassAt(100, "is-shown").click ->
        $roots.animate
            scrollTop: 0
        , 500


# ==================================================
# > HASH SCROLL
# ==================================================
$(window).on "hashchange", ->
    # No hash
    unless window.location.hash then return false

    # No element
    $el = $(window.location.hash)
    unless $el.length then return false

    # Animation scroll
    $roots.stop().animate
        "scrollTop": $el.offset().top
    , 400


# On anchor click
$("body").on "click.anchor", "a[href*='#']", -> setTimeout ->
        $(window).trigger "hashchange"
    , 100

# On new page load
setTimeout ->
    $(window).trigger "hashchange"
, 350

# ==================================================
# > MOBILE
# ==================================================
class MobileMenu
    constructor: (@$trigger, @$menu, @openClass) ->
        @$trigger.click => @toggle()

        @$root = $("html")

        # @$menu.find(".site-mobilenav__close").click => @close()

    toggle: ->
        @$root.toggleClass @openClass

    open: ->
        @$root.addClass @openClass

    close: ->
        @$root.removeClass @openClass

# ========== INIT ========== #
$ ->
    new MobileMenu $(".site-mobilenav__trigger"), $(".site-aside"), "is-mobilenav-open"




# =============================================================================
# > MAIN MENU
# =============================================================================
class Menu
    constructor: ->
        @$menu    = $(".site-aside__menu")
        @selector = ".site-aside__menu .is-current"

        # Set on pajax load
        Barba.Dispatcher.on "newPageReady", (o, s, ef, html) =>
            ids = $.map $(html).find(@selector), (item) -> "#" + $(item).attr("id")
            @setCurrent @$menu.find ids.join ", "

    setCurrent: ($item) ->
        @$menu.find(".is-current").removeClass "is-current"
        $item.addClass "is-current"


# > EVENT
menu = new Menu


# =============================================================================
# > VERSION CHANGE
# =============================================================================
$ -> $(".site-aside__versions").change ->
    window.location = window.location.href.replace /v[0-9]\.[0-9]+/, $(@).val()