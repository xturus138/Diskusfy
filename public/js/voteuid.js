import { getApps, initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-auth.js";

const firebaseConfig = {
    apiKey: "AIzaSyCyUNuYlWR-uFEUlXbL_-2Hm4t4u70Af4U",
    authDomain: "diskusfy.firebaseapp.com",
    projectId: "diskusfy",
    storageBucket: "diskusfy.firebasestorage.app",
    messagingSenderId: "525348408114",
    appId: "1:525348408114:web:56d959b778c20d63f3a52b",
    measurementId: "G-Y5MY8ZNNL0",
};

if (!getApps().length) {
    initializeApp(firebaseConfig);
}

const auth = getAuth();

onAuthStateChanged(auth, (user) => {
    if (user) {
        console.log("User is signed in (vote):", user);

        window.currentUserUid = user.uid;
    } else {
        console.log("No user is signed in (vote)");

        window.location.href = "/login";
    }
});
