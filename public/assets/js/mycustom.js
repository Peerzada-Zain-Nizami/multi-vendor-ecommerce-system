function bank() {
        var x = document.getElementById("bank");
        var y = document.getElementById("paypal");
        if (x.style.display === "none") {
            x.style.display = "block";
            y.style.display = "none";
        } else {
            x.style.display = "block";
        }
    }
function paypal() {
    var y = document.getElementById("bank");
    var x = document.getElementById("paypal");
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
    } else {
        x.style.display = "block";
    }
}
function rate() {
    var x = document.getElementById("rate");
    var y = document.getElementById("attach");
    if (x.style.display === "none") {
        x.style.display = "block";
        y.style.display = "none";
    } else {
        x.style.display = "block";
    }
}
function hide_1() {
    var x = document.getElementById("rate");
    var y = document.getElementById("attach");
    if (y.style.display === "none") {
        y.style.display = "block";
        x.style.display = "none";
    }
    else {
        y.style.display = "block";
    }
}
 function hide_2() {
     var x = document.getElementById("rate");
     var y = document.getElementById("attach");
     if (x.style.display === "block") {
         x.style.display = "none";
     }
     else if (y.style.display === "block")
     {
         y.style.display = "none";
     }
     else {
         x.style.display = "none";
     }
 }


