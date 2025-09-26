// Dropdown toggle by click
    const loginBtn = document.getElementById("loginBtn");
    const dropdownMenu = document.getElementById("dropdownMenu");

    loginBtn.addEventListener("click", function (e) {
      e.preventDefault();
      dropdownMenu.style.display =
        dropdownMenu.style.display === "block" ? "none" : "block";
    });

    // Close dropdown if clicked outside
    document.addEventListener("click", function (event) {
      if (!loginBtn.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.style.display = "none";
      }
    });


