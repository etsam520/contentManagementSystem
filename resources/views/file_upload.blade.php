<!DOCTYPE html>
<html>
<head>
<title>Laravel File Upload</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
.progress {
width: 100%;
background: #ddd;
margin-top: 10px;
}
.progress-bar {
width: 0%;
height: 20px;
background: green;
text-align: center;
color: white;
}
</style>
</head>
<body>


<h2>Upload File</h2>


<form id="uploadForm">
<input type="file" name="file" required>
<button type="submit">Upload</button>
</form>


<div class="progress">
<div class="progress-bar">0%</div>
</div>


<p id="status"></p>


<script>
const form = document.getElementById('uploadForm');
const progressBar = document.querySelector('.progress-bar');
const status = document.getElementById('status');


form.addEventListener('submit', function(e) {
e.preventDefault();


let formData = new FormData(this);


let xhr = new XMLHttpRequest();
xhr.open('POST', '{{ route('file.upload') }}', true);
xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);


xhr.upload.onprogress = function(e) {
if (e.lengthComputable) {
let percent = Math.round((e.loaded / e.total) * 100);
progressBar.style.width = percent + '%';
progressBar.innerText = percent + '%';
}
};


xhr.onload = function() {
if (xhr.status === 200) {
status.innerText = 'Upload successful';
} else {
status.innerText = 'Upload failed';
}
};


xhr.send(formData);
});
</script>
</html>