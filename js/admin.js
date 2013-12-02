var CHAT = {};
(function($, CHAT, undefined){
    
    CHAT.admin = {
        'last_time': new Date(),
        'get_users': function(){
            var source = $('#user-template').html(),
            template = Handlebars.compile(source);
            $.ajax({
                url: SITE_URL+'chatjs/get_active_users',
                dataType: 'json',
                success: function(json){
                    var html = "";
                    for(x in json){
                        var user = json[x];
                        user.last_activity = $.timeago(user.last_activity);
                        html += template(user);
                    }
                    $('#users-status').html(html);
                }
            });
            return this;
        },
        'longPoll': function(){
            var self = this;
            var source = $('#chat-template').html(),
            template = Handlebars.compile(source);
            CHAT.admin.get_users();
            $.ajax({
                url: SITE_URL+'chatjs/get_messages',
                dataType: 'json',
                type: 'GET',
                data:{
                    last_time : self.last_time
                },
                success: function(json){
                    self.last_time = json.last_time;
                    var html = "";
                    for(x in json.messages){
                        var user = json.messages[x];
                        user.date = $.timeago(user.date);
                        html += template(user);
                    }
                    $('#chat-window').append(html);
                    $('#chat-window').animate({ scrollTop: $("#chat-window").prop("scrollHeight") - $('#chat-window').height() }, 1);
                    $('.msj').emoticonize();
                },
                complete: function(){
                    CHAT.admin.longPoll();
                }
            });
            return this;
        },
        'sendMessage': function($form){
            $.post($form.attr('action'),$form.serialize(),function(json){
                $('#message').val('');
            },'json');
        }
    }
    
    $(document).on('submit','#chatjsSendMessage',function(){
        CHAT.admin.sendMessage($(this));
        return false;
    });
    
    $(document).on('click','.ban-user',function(){
        
    })
    
    $(function(){
        CHAT.admin.longPoll();
    })
    
})(window.jQuery, window.CHAT);