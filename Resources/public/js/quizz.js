

var folder_sound;
var game_json;



var json_game_data;
var question_id;
//var question_ordre;
var plateau_id ;
var enumeration=false;
var goodReponse;
var nb_reponses;

var mode_player;


var current_reponse_play;
var root;


var app = document.URL.indexOf( 'http://' ) === -1 && document.URL.indexOf( 'https://' ) === -1;
if ( app ) 
{
    root="";
}
else
{
    root="/";
}  


game_json=root+ "game/play/game_1.json";
folder_sound=root+"sons/";

function quitApp()
{
    if (navigator.app) {
        navigator.app.exitApp();
    } else if (navigator.device) {
        navigator.device.exitApp();
    } else 
    {
        window.location.href = "/";  
       
    }
}

$( document ).ready(function() {
    console.log( "ready!" );


    $.getJSON(game_json, function(game)
    {
        json_game_data=game;
        $('#game_title').html(game.name); 
        afficheBoards();
    }).fail(function() {
    alert( "Erreur json non trouvé : " + game_json );
  });
 
    
});


function afficheBoards()
{
              $('#boards').html(""); 
              $('#quit').show(); 
              $('#presentation').show(); 
              
                json_game_data.boards.forEach(function(board) 
                {
                    $('#boards').append("<li data-ordre=" + board.ordre +" class='board_select'>" + board.name); 



                board.questions.forEach(function(question) 
                {
            
                    if (localStorage.getItem('question' + question.id))
                    {
                        if (localStorage.getItem('question' + question.id)=="true")
                        {
                           icon_src=root +"img/icons/answer_good.png";
                        }
                        else
                        {
                           icon_src=root +"img/icons/answer_false.png";
                        }
                    }
                    else
                    {
                        icon_src=root +"img/icons/answer_no.png";
                    
                    } 
                    $('#boards').append("<img class='icon' src='"+ icon_src + "'>"); 

                });



                
                $('#boards').append("</li>"); 

                });
    
}

$(function() {
    
    var Player = document.getElementById('Player');
    var PlayerVideo = document.getElementById('PlayerVideo');
    var repondu=false;
    var playlist=null;
    

    
    function getCurrentBoard()
    {
        var tmp_board=false;
        json_game_data.boards.forEach(function(board) 
        {
            if (board.ordre===plateau_id)
            {
                tmp_board = board;
            }
        });
        return(tmp_board);
    }
    
    
    function getCurrentQuestion()
    {
        var tmp_question=false;
        getCurrentBoard().questions.forEach(function(question) 
        {
            if (question.id===question_id)
            {
                tmp_question = question;
            }
        });
        return(tmp_question);
    }
    
    
    function resetData()
    {
      localStorage.clear();
    }
     
    
    function playBoard()
    {
        mode_player="";
        Player.pause();
        
        $('#presentation').hide();
        $('#quit').hide();
        $('#back-to-board').hide();
        $('#question_next').hide();
        $('#boards').hide();
        $('#question_answer').hide();
        $('#board_title').html(getCurrentBoard().name); 
        $('#board_title').data("board_id",getCurrentBoard().id); 
        $('#board').html(""); 
        getCurrentBoard().questions.forEach(function(question) 
        {
            
            if (localStorage.getItem('question' + question.id))
            {
                if (localStorage.getItem('question' + question.id)=="true")
                {
                   icon_src=root +"img/icons/answer_good.png";
                }
                else
                {
                   icon_src=root +"img/icons/answer_false.png";
                }
            }
            else
            {
                icon_src=root +"img/icons/answer_no.png";
            }
            
            console.log(localStorage.getItem('question' + question.id));
            console.log(localStorage);
            
            
            
            //localStorage.setItem('name','Chris');
            $('#board').append("<li data-question_id='" + question.id + "'  data-ordre='" + question.ordre + "' class='question'><img class='icon' src='"+ icon_src + "'><span class='question_lib'>" + question.name + "</span></li>"); 
        });
        $('#board').show();
        $('#back-to-home').show();
        
        
    }
    
    function playVideoPlayer(src)
    {
            $('#video_question_player').show();
            PlayerVideo.src = src;
            PlayerVideo.play();
    }
    
    
    function playQuestionVideo()
    {
        if (getCurrentQuestion().questionVideoYoutube)
        {
            $('#video_question').html("<iframe  width='100%' height='auto' src='https://www.youtube.com/embed/" + getCurrentQuestion().questionVideoYoutube + "?autoplay=1' frameborder='0' allowfullscreen>");
        }
        if (getCurrentQuestion().questionVideo)
        {
            playVideoPlayer(root + getCurrentQuestion().questionVideo);
            goodReponse=false;
        }
        $('#video_question').append("<div class='skip-video-question-intro'>Passer à   la question</div>");
    }
    
    
    
    function playAnswerVideo()
    {
        $("#question_answer").hide();
        $("#question_answer_plus").hide();
        if (getCurrentQuestion().answerVideoYoutube)
        {
            $('#video_question').html("<iframe  width='100%' height='auto' src='https://www.youtube.com/embed/" + getCurrentQuestion().answerVideoYoutube + "?autoplay=1' frameborder='0' allowfullscreen>");
        }
        if (getCurrentQuestion().answerVideo)
        {
            playVideoPlayer(root + getCurrentQuestion().answerVideo);
        }
    }
    
    function afficheQuestionReponse()
    {
        var class_answer;

        $('#questionText').html(getCurrentQuestion().questionText); 
        $('#answers_proposal').html(""); 
            var i_ordre = 0;
                getCurrentQuestion().answers.forEach(function(answer) {
                    i_ordre=i_ordre+1;
                    if (answer.isGood)
                    {
                        class_answer="isGood";
                    }
                    else
                    {

                        class_answer="isFalse";
                    }
              $('#answers_proposal').append("<li id='answer_" + i_ordre + "' data-gof='" + answer.isGood + "' class='answer " + class_answer +"'>" + answer.answerText + '</li>'); 
          });
          $('#question_answers').show();
          repondu=false;
          playAnswers(getCurrentQuestion().answers);
        
    } 
                
    $('#footer').on('click', '#reset-data', function(ev) 
    {
            resetData();          
            alert("Les scores ont été effacés ! ");
    }); 
                
    $('#boards').on('click', 'li', function(ev) 
    {
        plateau_id =$(this).data("ordre");
        playBoard();
    }); 
    
    $('#tableau').on('click', '#board_title', function(ev) 
    {
//       alert("AAa : "+$(this).data("board_id"));
    }); 
    
    
    function playQuestion()
    {
        $('#question_title').html(getCurrentQuestion().name); 
        $( "#question_answer" ).html("");
        $( "#question_answer" ).hide();
        $( "#question_answer_plus" ).hide();
        $('#question_next').hide();
        $('#back-to-board').hide();
        $('#board').hide();
        $('#back-to-home').hide();

        if (getCurrentQuestion().questionHasVideo)
        {
            playQuestionVideo();
        }
        else
        {
            afficheQuestionReponse();
        }
        
    }
    
    $('#footer').on('click', '#btn-fini', function(ev) 
    {
        document.location.href="fini.html"; 
    }); 
    
    $('#footer').on('click', '#question_next', function(ev) 
    {
        question_id =getCurrentQuestion().nextQuestionId;
        stopAllVideos();
        Player.pause();
        playQuestion();
    }); 
    
    
    $('#board').on('click', 'li', function(ev) 
    {
        question_id =$(this).data("question_id");
        playQuestion();
    }); 
     
     function stopAllVideos()
     {
        $('#video_question').html("");
        $('#video_question_player').hide();
        PlayerVideo.pause();

     }
    $('.body-container').on('click', '.skip-video-question-intro', function(ev) 
    {
        afficheQuestionReponse();
        stopAllVideos();
    });
    
    $('.body-container').on('click', '.back-to-board', function(ev) 
    {
        playBoard();
        stopAllVideos();
    });

    
    $('#answers_proposal').on('click', 'li', function(ev) 
    {
        
        if (! repondu)
        {
            $('.question_answers').removeClass("nonrepondu");
            $('.question_answers').addClass("repondu");
            repondu = true;
            var player = document.getElementById('Player');
            if ($(this).data("gof"))
            {
               $(this).addClass("good");
                player.src = folder_sound + 'FX_Woosh_1.wav';
                goodReponse=true;
            }
            else
            {
                $('.answer.isGood').addClass("good");
                $(this).addClass("false");
                player.src = folder_sound + 'bad_answer.wav';
                goodReponse=false;
            }
            mode_player="";
            player.play();
            window.setTimeout( swowReponse, 800 );
             
        }
    });
    
    function swowReponse()
    {
        if (getCurrentQuestion().answerAudio)
        {
            console.log("swowReponse -> playAnswerAudio");
           playAnswerAudio();
        }
        else 
        {    
            console.log("swowReponse -> else answerAudio");
            if (getCurrentQuestion().answerHasVideo)
            {
                console.log("swowReponse -> play answerVideo");
                playAnswerVideo();
            }
            questionNextShow();
            
            $('#back-to-board').show();
        }
        
        if (goodReponse)
        {
            localStorage.setItem('question' + getCurrentQuestion().id,true);
        }
        else
        {
            localStorage.setItem('question' + getCurrentQuestion().id,false);
        }
       
        $( "#question_answers" ).hide( 500);
        $( "#question_answer" ).html( getCurrentQuestion().answerText );
        $( "#question_answer_plus" ).html( getCurrentQuestion().answerPlusText );
        $( "#question_answer" ).show(500);
        questionNextShow();
//        stopAllVideos();
        if (getCurrentQuestion().answerPlusText)
        {
            $( "#question_answer_plus" ).show(500);
        }
    }
    
    
    function questionNextShow()
    {
        if (getCurrentQuestion().nextQuestionId>0)
        {
            $('#question_next').show();
        }
        else
        {
            $('#btn-fini').show();
        }
    }
    
    function srcNumberAudio(number)
    {
        return(folder_sound + "numeros/" + number + ".wav");
    }
    function playNumberAudio(number)
    {
            enumeration=false;
            srcson= srcNumberAudio(number); 
            Player.src = srcson; 
            Player.play();
    }

    function playAnswerAudio()
    {
        Player.src = root + getCurrentQuestion().answerAudio; 
        Player.play();
        mode_player="play_answer_reading";
    }
    

    function playAnswers()
    {
        nb_reponses = getCurrentQuestion().answers.length;
        current_reponse_play=1;
        enumeration=true;
        mode_player="";
        if (getCurrentQuestion().questionAudio)
        {
            enumeration=true;
            Player.src = root + getCurrentQuestion().questionAudio; 
            $("#questionText").addClass("nowplaying");
            Player.play();
        }
        else
        {
            enumeration=false;
            playNumberAudio(1);
        }
    }
 

    Player.onended = function()
    {
        var srcson;


        console.log("mode_player : " + mode_player);
/*
        if (mode_player=="play_answer_video")
        {
            //console.log(root + getCurrentQuestion().answerAudio);
        }
        if (mode_player=="play_anwer")
        {
            //console.log(root + getCurrentQuestion().answerAudio);
        }
        else 
            */ 
        if (mode_player=="play_answer_plus")
        {
                console.log("play_answer_plus");
                playAnswerVideo();
                mode_player="play_answer_readed";
        }
        else  if (mode_player=="play_answer_reading")
        {
            console.log("play_answer_reading");
            
            if (getCurrentQuestion().answerPlusAudio)
            {
                console.log("answerPlusAudio");
                mode_player="play_answer_plus";
                Player.src = root + getCurrentQuestion().answerPlusAudio; 
                Player.play();
            }
            else
            {
                console.log("Pas answerPlusAudio");
                mode_player="play_answer_readed";
                playAnswerVideo();
                questionNextShow();
                $('#back-to-board').show();
            }
        }
        else if (! repondu)
        {
            console.log("Pas répondu");
            console.log(current_reponse_play +" < " + nb_reponses);
            if(current_reponse_play <= nb_reponses)
            {         
                if (enumeration)
                {
                    $(".nowplaying").removeClass("nowplaying");
                    $("#answer_"+ current_reponse_play).addClass("nowplaying");
                    playNumberAudio(current_reponse_play);
                }
                else
                {
                    //srcson=folder_sound + "plateau_0" + plateau_id + "/q" + question_ordre + "/answer_" + current_reponse_play + ".wav";
                    srcson=root + getCurrentQuestion().answers[(current_reponse_play-1)].answerAudio;
                    console.log("ICI");
                    console.log("current_reponse_play : " + current_reponse_play);
                    console.log(getCurrentQuestion());
                    console.log(getCurrentQuestion().answers[(current_reponse_play-1)]);
                    console.log(getCurrentQuestion().answers[(current_reponse_play-1)].answerAudio);
                    
                    Player.src = srcson; 
                    Player.play();
                    current_reponse_play++;
                     enumeration=true;
                } 
            }
            else
            {
                 $(".nowplaying").removeClass("nowplaying");
            }
        }
        else
        {
           console.log("NOTHING TO DO");
        }
        
    }    
    
    
});


