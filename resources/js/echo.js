import Echo from "@ably/laravel-echo";
import * as Ably from "ably";

window.Ably = Ably;
window.Echo = new Echo({
    broadcaster: "ably",
});

window.Echo.connector.ably.connection.on((stateChange) => {
    if (stateChange.current === "connected") {
        console.log("connected to ably server");
    }
});

// window.Echo = new Echo({
//     broadcaster: "reverb",
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: import.meta.env.VITE_REVERB_HOST,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? "https") === "https",
//     enabledTransports: ["ws", "wss"],
// });
