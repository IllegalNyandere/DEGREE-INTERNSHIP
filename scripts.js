function copyToClipboard() {
  /* Get the text field */
  var input = document.getElementById("myInput");

  /* Select the text field */
  input.select();
  input.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field to the clipboard */
  document.execCommand("copy");

  /* Alert the copied text */
  alert("Copied: " + input.value);
}

function cb(){
document.querySelectorAll(".copy-link").forEach((copyLinkParent) => {
  const inputField = copyLinkParent.querySelector(".copy-link-input");
  const copyButton = copyLinkParent.querySelector(".copy-link-button");
  const text = inputField.value;

  inputField.addEventListener("focus", () => inputField.select());

  copyButton.addEventListener("click", () => {
    inputField.select();
    navigator.clipboard.writeText(text);

    inputField.blur();

    inputField.value = "Copied!";
    setTimeout(() => (inputField.value = text), 2000);
  });
});
}