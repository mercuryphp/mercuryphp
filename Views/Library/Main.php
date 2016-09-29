<style>
    h1, h2 { font-weight: 400;  color: #3C385B;}
    .padding-right-40 { padding-right: 40px;}
    .table {
        display: table;
        width: 100%;
    }
    .table-cell {
        display: table-cell;
        vertical-align: top;
    }
    .class-list {
        display: table-cell;
    }
    
    .library {
        margin:0px 20px;font-size: 14px;
    }
    .library ul {
        list-style-type: none;
        margin: 4px 0px;
    }
    .library h3 {
        color: #3C385B;
        font-size: 14px;
        margin: 3px 0px;
    }
    .library a {
        color:#6D569E;
        text-decoration: none;
        font-size: 13px;
    }
    
    .method-list {
        border-collapse: collapse;
        width:100%;
    }
    .method-list tr td {
        border-bottom:1px solid #F2F2F2;
        font-family: 'Open Sans', 'Tahoma';
        font-size: 13px;
        padding:15px 10px;
        color:#333;
    }
    .method-list tr td p {
        margin: 5px 20px;
    }
    
    .method-list .keyword {
        color: #6D569E;
    }
    .method-list .name {
        color:#3C385B;
    }
    .method-list .td0 {
        background-color:#F9F9F9;
    }
    .width-200 { width:200px; }
</style>

<div class="table">
    <div class="table-cell padding-right-40 width-200">
        <div class="library">
            <?php foreach($library as $class=>$args){ ?>
                <h3><?php echo $class; ?></h3>
                <ul>
                    <?php foreach($args as $arg){ ?>
                    <li><a href="<?php echo $arg['url']; ?>"><?php echo $arg['name']; ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>

    <div class="table-cell">
    </div>
</div>