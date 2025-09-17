import "filepond/dist/filepond.min.css";
import * as FilePond from "filepond";
import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
  const inputs = document.querySelectorAll("input.filepond");
  inputs.forEach((input) => {
    FilePond.create(input, {
      allowMultiple: true,
      maxFiles: 5,
      storeAsFile: true, // los archivos se envían en el form estándar
      allowFileTypeValidation: true,
      acceptedFileTypes: ["image/*", "application/pdf"],
      allowFileSizeValidation: true,
      maxFileSize: "2MB",
      labelIdle:
        'Arrastra y suelta tus archivos o <span class="filepond--label-action">Explora</span>',
    });
  });
});
