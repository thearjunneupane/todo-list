function updateLiveDateTime() {
  const currentTime = new Date();
  const hours = currentTime.getHours();
  const minutes = currentTime.getMinutes();
  const seconds = currentTime.getSeconds();
  const ampm = hours >= 12 ? "PM" : "AM";

  const formattedHours = formatDigit(hours % 12 || 12); // Convert to 12-hour format
  const formattedTime = `${formattedHours}:${formatDigit(
    minutes
  )}:${formatDigit(seconds)} ${ampm}`;

  const currentDate = currentDateFormatted(currentTime);

  document.getElementById("live-datetime").innerHTML =
    `${currentDate}` + "<br>" + `${formattedTime}`;
}

function formatDigit(digit) {
  return digit < 10 ? `0${digit}` : digit;
}

function currentDateFormatted(date) {
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  return date.toLocaleDateString("en-US", options);
}

// Initial call to display time and date
updateLiveDateTime();

// Update time and date every second
setInterval(updateLiveDateTime, 1000);
