import axios from "axios";
window.axios = axios;
window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// Echo and Pusher setup
import Echo from "laravel-echo";
import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? "mt1",
    wsHost: import.meta.env.VITE_PUSHER_HOST
        ? import.meta.env.VITE_PUSHER_HOST
        : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? "http") !== "http",
    enabledTransports: ["ws", "wss"],
    authEndpoint: "/broadcasting/auth",
});

// New client created
const userIdMeta = document.querySelector('meta[name="user-id"]');

// if (userIdMeta) {
//     alert('User ID meta tag found! User ID is: ' + userIdMeta.getAttribute('content'));
// } else {
//     alert('User ID meta tag NOT found!');
// }
const userId = userIdMeta ? userIdMeta.getAttribute("content") : null;

if (userId) {

 window.Echo.private(`new-employee`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );

window.Echo.private(`employee-requests`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );
    window.Echo.private(`employee-request-status`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );
    window.Echo.private(`employee-alerts`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );
          window.Echo.private(`employee-login-ip`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );
          window.Echo.private(`birthday`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );
      window.Echo.private(`employee-deductions`).listen(
        ".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",
        (data) => {
            Toast.fire({
                icon: "success",
                title: "✅ تم قبول الطلب",
                html: `<div>
                    <p>${data.message}</p>
                    <a href="${data.url}" class="text-blue-500 hover:underline">
                        عرض الطلب الموافق عليه
                    </a>
                </div>`,
                timer: 8000,
            });
        }
    );
Echo.private(`participant.${encodedType}.${userId}`)
    .listen('.Namu\\WireChat\\Events\\NotifyParticipant', (e) => {
        console.log(e);
    });
}

import "./echo";

