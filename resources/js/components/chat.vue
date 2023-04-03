<template>
    <div class="chat card">
        <div class="scrollable card-body" ref="hasScrolledBottom">
            <template v-for="message in messages">
                <div class="message message-receive" v-if="user.id != message.user.id">
                    <p>
                        <strong class="primary-font">
                            {{  message.user.name }}
                        </strong>
                            {{  message.message }}
                    </p>
                </div>
                <div class="message message-send" v-else>
                    <p>
                        <strong class="primary-font">
                            {{  message.user.name }}
                        </strong>
                            {{  message.message }}
                    </p>
                </div>
            </template>
        </div>
        <div class="chat-form input-group">
            <input type="text" id="btn-input" name="message" class="form-control input-sm message-" placeholder="type your message here ..." v-model="newMessage" @keyup.enter="addMessage">
            <span class="input-group-btn">
                <button class="btn btn-primary" id="btn-chat" @click="addMessage">
                    Send
                </button>
            </span>
        </div>
    </div>
</template>

<script>
import {ref, onMounted, onUpdated } from "vue";
import axios from 'axios'
export default {
    props:['user'],
    setup(props) {
        let messages = ref([]);
        let newMessage = ref('');
        let hasScrolledBottom = ref('');

        onMounted(()=>{
            fetchMessages();
        })
        onUpdated(() => {
            scrollBottom();
        })
        Echo.private('chat-channel')
            .listen('SendMessage', (e)=> {
                console.log(e);
                messages.value.push({
                    message: e.message.message,
                    user: e.user
                })
            })
        const fetchMessages = async() => {
            axios.get('/messages').then(response => {
                messages.value = response.data;
            });
        }
        const addMessage = async()=> {
            let user_message = {
                user: props.user,
                message: newMessage.value
            };
            console.log(user_message);
            messages.value.push(user_message);
            axios.post('/messages',user_message).then(response => {
                console.log(response.data);
            })
            newMessage.value = '';
        }

        const scrollBottom = () =>{
            if(messages.value.length > 1){
                let el = hasScrolledBottom.value;
                el.scrollTop = el.scrollHeight;
            }
        }
        return {
            messages,
            newMessage,
            addMessage,
            fetchMessages,
            hasScrolledBottom,
        }
    }
}
</script>
