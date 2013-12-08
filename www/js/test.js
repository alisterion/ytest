$(document).ready(function() {
    var answerNum = 0;

    var max_question = $("#max_question").val()


    $('#answers .flash-error').die('click').live('click', function() {
        $('#answers div').removeClass('flash-success');
        $('#answers div').addClass('flash-error');
        $(this).removeClass('flash-error');
        $(this).addClass('flash-success');
        answerNum = $(this).attr('rel');
        //$('#send_answer').show();
    });




    function set_active_quest(num) {
        $("#question_list .quest_list.active").removeClass("active");
        var now_quest = $("#question_list .quest_list")[num - 1];
        $(now_quest).addClass("active")
    }

    function sendAnswer(q_num) {
        $.ajax({
            url: "/site/answer",
            type: "POST",
            dataType: "json",
            data: {
                user_answer: answerNum,
                quest_num: q_num
            },
            beforeSend: function() {
                $("#overley").show();

            },
            complete: function(data) {
                $("#overley").hide();
            },
            error: function() {
                $("#overley").hide();
                alert("Error");
            },
            success: function(data) {

                $("#overley").hide();
                if (data.status == "finish") {
                    //clearInterval(intervalID);
                    showUserStat();
                    return false;
                }
                if (data.status) {
                    //$("#main-content").html(data.content);
                    var now_quest = $("#question_list .quest_list")[q_num - 1];
                    set_active_quest(q_num + 1);
                    $(now_quest).removeClass("flash-notice")
                    $(now_quest).addClass("flash-success")
                    check_for_end()
                }

            }
        })
        answerNum = 0;
    }


    $("#send_answer").die('click').live('click', function(event) {
        var q_num = $(this).data("question");
        sendAnswer(q_num);
        return false;
    });



    function showUserStat() {
        $.ajax({
            url: "/site/UserStat",
            type: "POST",
            dataType: "json",
            beforeSend: function() {

                $("#overley").show();
            },
            complete: function(data) {
                $("#overley").hide();
            },
            error: function() {
                $("#overley").hide();

            },
            success: function(data) {
                $("#main-content").html(data.content);
                $("#overley").hide();
                $("#question_list").hide();
            }
        })
    }

    var timeInterval = null;
    var d_start = null;
    var d_end = null;
    var d_now = null;

    function calc_time(d_start, d_end, d_now) {
        var lasts_seconds = (d_end - d_now) / 1000;
        var hour = Math.floor(lasts_seconds / 3600);
        var min = Math.floor(lasts_seconds / 60) - hour * 60;
        var second = lasts_seconds % 60;
        var temeStr = "";
        if (hour > 0) {
            temeStr += hour + " : ";
        }
        if (min > 0) {
            temeStr += min + " : ";
        }
        if (second > 0) {
            if (second < 10) {
                temeStr += "0" + second;
            } else {
                temeStr += second;
            }

        }
        if (hour === 0 && min === 0 && lasts_seconds === 0) {
            finishTest()
        }
        return temeStr;
    }

    function user_time_calc() {
        d_now_mc = d_now.getTime();
        d_now = new Date(d_now_mc + 1000);
        temeStr = calc_time(d_start, d_end, d_now);
        $('#timer_text').html(temeStr);
    }

    function user_time_all() {
        $.ajax({
            url: "/site/getUserStats",
            type: "POST",
            dataType: "json",
            beforeSend: function() {


            },
            complete: function(data) {

            },
            error: function() {


            },
            success: function(data) {
                var start = data["start_test_at"] * 1000;
                var end = data["end_test_at"] * 1000;
                var now = data["now"] * 1000;
                d_start = new Date(start);
                d_end = new Date(end);
                d_now = new Date(now);
                temeStr = calc_time(d_start, d_end, d_now);
                $('#timer_text').html(temeStr);
                timeInterval = window.setInterval(user_time_calc, 1000);
            }
        })
    }
    user_time_all();
    //var intervalID = window.setInterval(user_time, 1000);


    function getQuestion(num) {
        $.ajax({
            url: "/site/getQuestion",
            type: "POST",
            dataType: "json",
            data: {
                quest_num: num
            },
            beforeSend: function() {
                $("#overley").show();

            },
            complete: function(data) {
                $("#overley").hide();
            },
            error: function() {
                $("#overley").hide();
                alert("Error");
            },
            success: function(data) {

                $("#overley").hide();
                if (data.status == "finish") {
                    //clearInterval(intervalID);
                    showUserStat();
                    return false;
                }
                if (data.status) {
                    $("#main-content").html(data.content);
                    set_active_quest($("#send_answer").data("question"));
                }

            }
        })
        answerNum = 0;
    }

    function fill_q_list(list) {
        var cont = $("#question_list");

        for (var i in list) {
            var el = document.createElement("div");
            jEl = $(el);
            jEl.html(i);
            jEl.addClass("quest_list");
            jEl.attr("data-num", i);
            if (list[i] == 0) {
                jEl.addClass("flash-notice");
            } else {
                jEl.addClass("flash-success");
            }
            cont.append(el)
        }



        $("#question_list .quest_list")
                .die("click")
                .live("click", function() {
                    getQuestion($(this).data("num"));
                })
    }


    function user_question_list() {
        $.ajax({
            url: "/site/getQuestList",
            type: "POST",
            dataType: "json",
            beforeSend: function() {


            },
            complete: function(data) {

            },
            error: function() {


            },
            success: function(data) {
                fill_q_list(data);
                set_active_quest($("#send_answer").data("question"));
            }
        })
    }

    user_question_list()

    $("#get_prew").die().live("click", function() {
        event.preventDefault()
        var now = $("#send_answer").data("question");
        if (now <= 1) {
            getQuestion(parseInt(max_question, 10));
        } else {
            getQuestion(now - 1);
        }
    })


    $("#get_next").die().live("click", function(event) {
        event.preventDefault()
        var now = $("#send_answer").data("question");
        var max = parseInt(max_question, 10);
        if (answerNum > 0) {
            sendAnswer(now);

            get_first_not_answer(now, max)
            return false;
        }
        if (parseInt(max_question, 10) <= now) {
            getQuestion(1);
        } else {
            getQuestion(now + 1);
        }

    })

    function finishTest() {


        $.ajax({
            url: "/site/FinishTest",
            type: "POST",
            dataType: "json",
            beforeSend: function() {


            },
            complete: function(data) {

            },
            error: function() {


            },
            success: function(data) {
                showUserStat();
            }})

    }

    $("#finish_test").die().live("click", function(event) {
        if (confirm("Finish TEST???")) {
            finishTest();
        }
    })

    function check_for_end() {
        if ($("#question_list div.flash-notice").length <= 0) {
            finishTest();
        }
    }


    function check_for_not_answer() {
        return $("#question_list div.flash-notice");
    }


    function get_first_not_answer(now, max) {
        not_answer_array = check_for_not_answer();
        if (max <= now) {
            getQuestion(parseInt($(not_answer_array[0]).data("num"), 10));
        }
        if (now + 1 < max) {
            var res = $("#question_list div").slice(now, max);
            $(res).each(function() {
                if ($(this).hasClass("flash-notice")) {
                    getQuestion(parseInt($(this).data("num"), 10))
                    return false;
                }
            })
        }
    }

    function user_time() {
        $.ajax({
            url: "/site/getUserInfo",
            type: "POST",
            dataType: "json",
            beforeSend: function() {


            },
            complete: function(data) {

            },
            error: function() {


            },
            success: function(data) {
                var nowDate = new Date(data.now);
                var endDate = new Date(data.end);
                var sec = (endDate - nowDate) / 1000;
                var hour = Math.floor(sec / 3600);
                var min = Math.floor(sec / 60) - hour * 60;
                var second = sec % 60;
                var temeStr = '';
                if (hour > 0) {
                    temeStr += hour + " : ";
                }
                if (min > 0) {
                    temeStr += min + " : ";
                }
                if (second > 0) {
                    if (second < 10) {
                        temeStr += "0" + second;
                    } else {
                        temeStr += second;
                    }

                }
                $('#timer_text').html(temeStr);

                if (hour == 0 && min == 0 && sec == 0) {
                    sendAnswer();
                }
                sec = 0;
            }
        })
    }


})
