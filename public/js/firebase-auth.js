import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import {
    getAuth,
    GoogleAuthProvider,
    signInWithPopup,
} from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyByi25CZP3Fh34pynq8HePjur3EmxO8BTY",
    authDomain: "sinemu-final.firebaseapp.com",
    projectId: "sinemu-final",
    storageBucket: "sinemu-final.appspot.com",
    messagingSenderId: "587663848700",
    appId: "1:587663848700:web:42ef099c3a2377dba0d379",
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

window.loginWithGoogle = async () => {
    try {
        const result = await signInWithPopup(auth, provider);
        const token = await result.user.getIdToken();

        const res = await fetch("/auth/google/firebase", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({ token }),
        });

        if (res.ok) {
            window.location.href = "/dashboard";
        } else {
            let message = "Login gagal (server)";
            try {
                const data = await res.json();
                if (data?.message) {
                    message = data.message;
                }
            } catch (e) {
                // ignore JSON parsing error
            }
            alert(message);
        }
    } catch (err) {
        console.error(err);
        alert("Login dibatalkan");
    }
};
