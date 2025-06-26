document.addEventListener("DOMContentLoaded", function () {
    const wrapper = document.getElementById("notification_wrapper");
    if (wrapper) {
        const list = wrapper.querySelector(".vn-notification-list");
        const speed = wrapper.getAttribute("data-speed") || 18;
        list.style.setProperty('--vn-speed', speed + 's');
    }
});
