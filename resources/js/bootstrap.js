import axios from "axios";
window.axios = axios;
import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});
// moved to modalPost.blade.php
// document.addEventListener("DOMContentLoaded", () => {
//     const postId = "{{ $p->_id ?? $p->id }}";

//    window.Echo.channel(`post.${postId}`).listen(".comment.sent", (e) => {
//     if (e.comment.user_id !== window.userId) {
//         renderComment({
//             postId :postId,
//             pic: e.comment.user?.pic,
//             user_name: e.comment.user?.name,
//             comment: e.comment.comment,
//             created_at: new Date(e.comment.created_at).toLocaleString()
//         });
//     }
// });

// });
