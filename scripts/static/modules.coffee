import Barba from "barba.js"
import global from "./../modules/global.coffee"

export default Barba.BaseView.extend
    namespace: "page"

    onEnterCompleted: ->
        global.in()


    # onLeave: ->
    # onEnter: ->
    # onLeaveCompleted: ->