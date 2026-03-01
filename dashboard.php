<?php include 'config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dairy Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>

<body class="d-flex flex-column vh-100">

    <?php include 'header.php'; ?>

    <div class="d-flex flex-grow-1  overflow-hidden">
        <div class="bg-light border-end" style="width: 250px;">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex-fill overflow-auto p-3" id="content-area"
        style="background-image: url('assets/img/hed.jpg'); background-size: cover; background-position: center; background-repeat: no-repeat;">
            <div class="w-25 text-success bg-success text-white fs-3 d-flex justify-content-center align-items-center mb-3 mt-5">
                <h4>Summary of Data</h4>
            </div>

            <div id="summary" class="row mb-4"></div>

            <div class="w-25 text-success bg-light fs-3 d-flex justify-content-center align-items-center mb-3 mt-5  border  border-success border-5">
                <h4>Recent Activities</h4>

            </div>

            <div id="recentActivity" class="mb-4"></div>

            <div class="w-25 text-success bg-light fs-3 d-flex justify-content-center align-items-center mb-3 mt-5  border  border-success border-5" >
                <h4>Image Records</h4>

            </div>

            <div class="table-responsive mb-4">
                <table class="table table-bordered border-primary">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody id="imageRecords"></tbody>
                </table>
            </div>


             <div class="w-25 bg-success text-white  fs-3 d-flex justify-content-center align-items-center mb-3 mt-5">
            <h4>Milk Production Chart</h4>

            </div>

            <div id="barChart" style="width:100%;height:400px;"></div>

        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            fetch("dairy_dashboard_data.php?fetch=1")
                .then(res => res.json())
                .then(data => {
                    document.getElementById('summary').innerHTML = `
        <div class="col-md-3 ">
            <div class="card p-3 text-center text-light bg-success ">
                <h5>Farmers</h5><h3>${data.farmers}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center text-light bg-success">
                <h5>Employees</h5><h3>${data.employees}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3 text-center text-light bg-success">
                <h5>Milk Records</h5><h3>${data.milk}</h3>
            </div>
        </div>
        <div class="col-md-3"><div class="card p-3 text-center text-light bg-success"><h5>Payments</h5><h3>${data.payments}</h3></div></div>`;

                    document.getElementById('recentActivity').innerHTML = data.recent.map(item => `
        <div class="row border p-2 mb-2 rounded bg-light  border  border-success border-5 text-success f">
            <div class="col-md-4"><strong>${item.category}:</strong> ${item.detail}</div>
            <div class="col-md-4"><strong>Date:</strong> ${item.date}</div>
        </div>`).join('');
                });

            fetch("dairy_dashboard_data.php?image=1")
                .then(res => res.json())
                .then(imgs => {
                    document.getElementById("imageRecords").innerHTML = imgs.map(img => `
        <tr>
            <td>${img.id}</td>
            <td>${img.category}</td>
            <td>${img.name}</td>
            <td>${img.type||''}</td>
            <td>${img.image?'<img src="'+img.image+'" width="50">':''}</td>
        </tr>`).join('');
                });

            google.charts.load("current", {
                packages: ['corechart', 'bar']
            });
            google.charts.setOnLoadCallback(() => {
                fetch('dairy_dashboard_data.php?chart=milkPerFarmer')
                    .then(res => res.json())
                    .then(farmers => {
                        const colors = ['#e6194B', '#3cb44b', '#ffe119', '#4363d8', '#f58231', '#911eb4', '#46f0f0', '#f032e6', '#bcf60c', '#fabebe'];
                        var data = new google.visualization.DataTable();
                        data.addColumn('string', 'Farmer');
                        data.addColumn('number', 'Milk (L)');
                        data.addColumn({
                            type: 'string',
                            role: 'style'
                        });
                        farmers.forEach((f, i) => data.addRow([f.name, parseFloat(f.total_milk), 'color: ' + colors[i]]));
                        var chart = new google.charts.Bar(document.getElementById('barChart'));
                        chart.draw(data, google.charts.Bar.convertOptions({
                            title: 'Milk Production for 10 Farmers',
                            legend: 'none',
                            bars: 'horizontal',
                            bar: {
                                groupWidth: "70%"
                            }
                        }));
                    });
            });
        });
    </script>
    <script>
        function loadPage(page) {
            fetch(page)
                .then(response => response.text())
                .then(html => {
                    document.getElementById("content-area").innerHTML = html;
                })
                .catch(() => {
                    document.getElementById("content-area").innerHTML =
                        `<div class='alert alert-danger'>Failed to load page</div>`;
                });
        }
    </script>

</body>

</html>