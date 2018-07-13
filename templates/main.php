<html>
<head>
    <meta charset="utf-8" />
    <?php 
        style('usage_amount', 'bootstrap');
        script('usage_amount', 'history');
    ?>
</head>
<style>
  .title{
    padding: 10px 10px 10px 0px;
  }
  td,th{
    text-align:center;
  }
</style>
<body>

<div class="container">
    <div class="title">
    <a href="exportCSV/day" class="btn btn-success">匯出每日</a>
    <a href="exportCSV/month" class="btn btn-success">匯出每月</a>   
    <a><?php echo "現在時間 " . date("Y-m-d H:i:s");  ?></a>
    </div>
    <div >
        <table id="history" class="table table-bordered table-sm">
            <thead class="thead-dark">
                <th scope="col">使用者</th>
                <th scope="col">E-mail</th>
                <th scope="col">用量</th>
                <th scope="col">總使用量</th>
                <th scope="col">時間</th>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>