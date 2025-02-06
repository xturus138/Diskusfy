import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import {
    getAuth,
    createUserWithEmailAndPassword,
    signInWithPopup,
    GoogleAuthProvider,
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyCyUNuYlWR-uFEUlXbL_-2Hm4t4u70Af4U",
    authDomain: "diskusfy.firebaseapp.com",
    projectId: "diskusfy",
    storageBucket: "diskusfy.firebasestorage.app",
    messagingSenderId: "525348408114",
    appId: "1:525348408114:web:56d959b778c20d63f3a52b",
    measurementId: "G-Y5MY8ZNNL0",
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

// Handle email/password registration
document
    .getElementById("register-form")
    .addEventListener("submit", async (e) => {
        e.preventDefault();

        const email = document.getElementById("email").value;
        const password = document.getElementById("password").value;
        const username = document.getElementById("username").value;
        const nama = document.getElementById("nama").value; // Tambahkan field nama

        try {
            const userCredential = await createUserWithEmailAndPassword(
                auth,
                email,
                password
            );

            // Setelah registrasi berhasil, kirim data ke server Laravel
            const response = await fetch("/register", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    email,
                    username,
                    nama,
                    uid: userCredential.user.uid, // Kirim UID dari Firebase
                }),
            });

            const data = await response.json();
            if (data.success) {
                alert("Registrasi berhasil! Anda sekarang dapat masuk.");
                window.location.href = "/login"; // Redirect ke halaman login
            } else {
                alert("Gagal menambahkan pengguna.");
            }
        } catch (error) {
            alert("Registrasi gagal: " + error.message);
        }
    });

// Handle Google sign-up
document.getElementById("google-signup").addEventListener("click", async () => {
    try {
        const result = await signInWithPopup(auth, provider);
        const user = result.user;
        const email = user.email;
        const uid = user.uid;

        // Gunakan email untuk username dan nama
        const username = email;
        const nama = email;

        // Kirim data ke server Laravel
        const response = await fetch("/register", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                email,
                username,
                nama,
                uid, // UID dari Firebase
            }),
        });

        const data = await response.json();
        if (data.success) {
            alert(
                "Registrasi dengan Google berhasil! Selamat datang, " + email
            );
            window.location.href = "/"; // Redirect ke halaman utama atau halaman lain sesuai kebutuhan
        } else {
            alert("Gagal menambahkan pengguna.");
        }
    } catch (error) {
        alert("Login dengan Google gagal: " + error.message);
    }
});
