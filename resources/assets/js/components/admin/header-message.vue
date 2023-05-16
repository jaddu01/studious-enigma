<template>
    <li role="presentation" class="dropdown" >
    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
        <i class="fa fa-envelope-o"></i>
        <span class="badge bg-green" >{{messages.length}}</span>
    </a>
    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
        <li v-for="message in messages">
            <a>
                <span class="image">
                    <img v-bind:src="message.data.image" alt="Profile Image"/>
                </span>
                <span>
                    <span>{{message.data.order_code}}</span>
                    <span class="time"><timeago :datetime="message.created_at" :auto-update="60"></timeago></span>
                </span>
                <span class="message">
                   {{message.data.message}}
                </span>
            </a>
        </li>
        <li v-if="messages.length > 0">
            <div class="text-center">
                <a v-bind:href="baseURL+'/admin/notification'">
                    <strong>See All</strong>
                    <i class="fa fa-angle-right"></i>
                </a>
            </div>
        </li>

    </ul>
    </li>
</template>

<script>

    export default {
        data() {
            return {
                messages: [

                    ],
                baseURL: window.axios.defaults.baseURL,
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.time = Date.now();
            this.listenForChanges();
            this.getNotification();
        },
        methods: {
            getNotification(postName, postDesc) {

                axios.get('/api/v1/notification/48', {
                    title: postName, description: postDesc
                }).then( response => {
                    if(response.data) {
                      this.messages= response.data;

                    }
                })
            },
            listenForChanges() {
                Echo.private('App.User.48')
                    .notification((notification) => {
                        this.messages.push({created_at:notification.created_at.date,data:{order_code:notification.order_code,image:notification.image,message:notification.message}});
                    });

            }
        }
    }
</script>
