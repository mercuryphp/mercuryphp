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
        padding-left: 25px;
    }
    .library h3 {
        color: #3C385B;
        font-size: 13px;
        margin: 7px 0px;
        cursor: pointer;
        
    }
    .library a {
        color:#403861;
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
    .hide { display: none; }
</style>

<script type="text/javascript">
    function expand(sender){
        sender.nextElementSibling.style.display = 'block';
    }
</script>

<div class="table">
    <div class="table-cell padding-right-40 width-200">
        <div class="library">
            <?php foreach($library as $class=>$args){ ?>
                <h3 onclick="expand(this)"><?php echo $class; ?></h3>
                <ul class="<?php echo strtolower($class) == \System\Core\Str::set($request->getUrl()->getPathSegments()[1])->getLastIndexOf('.') ? '' : 'hide'; ?>">
                    <?php foreach($args as $arg){ ?>
                    <li><a href="<?php echo $arg['url']; ?>"><?php echo $arg['name']; ?></a></li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </div>
    </div>

    <div class="table-cell">
        <div>
            <h1><?php echo $className; ?></h1>
            <p><?php echo $classDescription; ?></p>
        </div>
        
        <h2>Methods</h2>
        <table class="method-list">
            <?php $idx =0; foreach($methods as $method => $args){ ?>
                <?php if($args['description']){ ?>
                    <tr>
                        <td class="td<?php echo $idx % 2; ?>">
                            <?php $parmStr = ''; foreach($args['params'] as $params){ ?>
                                <?php $parmStr .= '<span class="keyword">' . ($params['type'] ? $params['type'] : 'mixed') . '</span> $'.$params['name'] . ", "; ?>
                            <?php } ?>
                            <strong class="keyword"><?php echo join(' ', $args['modifiers']); ?> function</strong> <strong class="name"><?php echo $method; ?></strong>( <?php echo trim($parmStr, ', '); ?> ) : <?php echo str_replace('\\', '.', $args['return']) ?  : 'void'; ?> <strong>{</strong>
                            <p><?php echo $args['description']; ?></p>
                            <strong>}</strong>
                        </td>
                    </tr>
                <?php } ?>
            <?php $idx++; } ?>
        </table>
    </div>
</div>