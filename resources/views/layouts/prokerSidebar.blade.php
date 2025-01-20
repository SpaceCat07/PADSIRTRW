<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Kerja</title>

    <!-- Bootstrap and CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

    <!-- Fonts -->
    <style>

    </style>
</head>

<body>

    <div class="sidebar">
        <!-- RT/RW Horizontal Toggle -->
        <div class="toggle-container">
            <div class="toggle-item active" id="rt-toggle">RT</div>
            <div class="vertical-line"></div>
            <div class="toggle-item" id="rw-toggle">RW</div>
        </div>

        <!-- Sidebar Main Content -->
        <!-- Sidebar Main Content -->
        <div class="sidebar-content active" id="rt-content">
            <div class="sidebar-item active">
                Mendatang
            </div>
            <div class="sidebar-item">
                Sudah Terlaksana
            </div>
        </div>

        <div class="sidebar-content" id="rw-content">
            
        </div>
    </div>

    <script>
        // JavaScript to toggle between RT and RW
        const rtToggle = document.getElementById('rt-toggle');
        const rwToggle = document.getElementById('rw-toggle');
        const rtContent = document.getElementById('rt-content');
        const rwContent = document.getElementById('rw-content');

        rtToggle.addEventListener('click', () => {
            rtToggle.classList.add('active');
            rwToggle.classList.remove('active');
            rtContent.classList.add('active');
            rwContent.classList.remove('active');
        });

        rwToggle.addEventListener('click', () => {
            rwToggle.classList.add('active');
            rtToggle.classList.remove('active');
            rwContent.classList.add('active');
            rtContent.classList.remove('active');
        });

        // Optional: Add click event to sidebar items if needed
        const rtItems = rtContent.getElementsByClassName('sidebar-item');

        for (let item of rtItems) {
            item.addEventListener('click', () => {
                for (let i of rtItems) i.classList.remove('active');
                item.classList.add('active');
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>
