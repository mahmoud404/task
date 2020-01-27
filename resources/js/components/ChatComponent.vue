<template>
    <ul class="chat-list">
        <li v-for="message in messages">
            <div class="chat-body">
                <div class="chat-message">
                    <h5>{{message.user.name}}</h5>
                    <p>{{message.content}}</p>
                </div>
            </div>
        </li>
        <li>
            <div class="chat-body">
                <div class="chat-message">
                    <textarea class="form-control form-control-rounded" v-model="message" @keyup.enter="sendMessage"  placeholder="Type your message" name="message" id="message" cols="30" rows="3"></textarea>
                </div>
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        data() {
            return {
                messages:[],
                message:null,
            };
        },
        mounted() {
            this.getMessages();
            this.listenToChat();
        },
        methods:{
            getMessages(){
                let url = "/messages";
                axios.get(url).then(response  => {
                    this.messages = response.data.data;
                });
            },
            listenToChat(){
                window.Echo.channel('laravel_database_chat')
                    .listen('.send_message', (message) => {
                        this.messages.push(message);
                        if (! ('Notification' in window)) {
                            alert('Web Notification is not supported');
                            return;
                        }

                        Notification.requestPermission( permission => {
                            let notification = new Notification('New Message alert!', {
                                body: message.content, // content for the alert
                                icon: "https://pusher.com/static_logos/320x320.png" // optional image url
                            });
                        });
                    });
            },
            sendMessage(){
                if (!this.message) {
                    return;
                }

                let data = {
                    message: this.message,
                };
                let url = "/messages";
                axios.post(url,data).then(response => {
                    if (response)
                    {
                        this.messages.push(response.data.data);
                    }
                });
                this.message = null;
            },

        },
    }
</script>
