<section class="title">
    <h4>Chat JS</h4>
</section>
<section class="item">
    <div class="content">
        <div class="one_half">
            <div class="tabs">
                <ul class="tab-menu">
                    <li><a href="#panel1"><span><?php echo lang('chatjs:activeUsers') ?></span></a></li>
                    <li><a href="#panel2"><span><?php echo lang('chatjs:bannedUsers') ?></span></a></li>
                    <li><a href="#panel3"><span><?php echo lang('chatjs:bannedIPs') ?></span></a></li>
                </ul>
                <div id="panel1" style="height: 400px; overflow-y: scroll; margin-botton:10px !important;">
                    <ul class="ChatjsUsers" id="users-status">

                    </ul>
                </div>
                <div id="panel2">

                </div>
                <div id="panel3">

                </div>
            </div>
        </div>
        <div class="one_half">
            <div class="chatjsWindow" id="chat-window">

            </div>
            <div class="chatjs_conrols">
                <form id="chatjsSendMessage" action="<?php echo site_url('chatjs/post_message') ?>">
                    <input type="text" id="message" name="message" placeholder="<?php echo lang('chatjs:input_placeholder') ?>" />
                    <input type="hidden" name="user_id" value="<?php echo $user->id ?>" />
                    <input type="hidden" name="is_guest" value="0" />
                    <input type="hidden" name="guest_name" value="" />
                    <button type="submit" style="display: none" class="btn blue">Enviar</button>
                </form>
            </div>
        </div>
    </div>
</section>
<script type="text/x-handlebars-template" id="user-template">
    <li>
        <strong>{{ user }}</strong> <br />
        <?php echo lang('chatjs:last_activiy') ?>: {{ last_activity }}
        <div class="action-buttons">
            <a href="javascript:void(0)" data-user-id="{{ user_id }}" class="btn red"><?php echo lang('chatjs:ban_user') ?></a>
            <a href="javascript:void(0)" data-user-ip="{{ user_ip }}" class="btn red"><?php echo lang('chatjs:ban_ip') ?></a>
        </div>
    </li>
</script>
<script id="chat-template" type="text/x-handlebars-template">
    <div class="msj">
        <img src="{{ avatar }}" />
        <strong>{{ user }}:</strong>
        {{ message }}
        <span class="dateChat">{{ date }}</span>
        <div style="clear: both"></div>
    </div>
</script>