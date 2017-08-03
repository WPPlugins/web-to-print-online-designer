<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly  
$limit = $row * $per_row;
$k = 0;
echo "<p>".$des."</p>";
if(count($templates)):
    echo '<ul class="nbdesigner-gallery">';
    foreach ($templates as $temp): ?>
    <?php if($k % $per_row == 0) echo '<li class="nbdesigner-container">';?>
    <div class="nbdesigner-item">
        <div class="nbdesigner-con">
            <div class="nbdesigner-top">
                <img src="<?php echo $temp['image']; ?>" class="nbdesigner-img"/>
            </div>
            <div class="nbdesigner-hover">
                <div class="nbdesigner-inner">
                    <a href="<?php echo add_query_arg(array('nbds-adid' => $temp['adid']), get_permalink( $temp['id'] )); ?>" class="nbdesigner-link" >View design<span>→</span></a>
                </div>
            </div>            
        </div>
    </div>
    <?php if($k % $per_row == ($per_row -1)) echo '</li>';?>
    <?php 
    $k ++;
    endforeach;
    echo '</ul>'; ?>
<?php else: ?>    
    <?php _e('No template', 'nbdesigner'); ?>
<?php endif; ?>
<?php if(($total > $limit) && $pagination): ?>
<?php  
    require_once NBDESIGNER_PLUGIN_DIR . 'includes/class.nbdesigner.pagination.php';
    $paging = new Nbdesigner_Pagination();
    $url = '';
    $config = array(
        'current_page'  => isset($page) ? $page : 1, 
        'total_record'  => $total,
        'limit'         => $limit,
        'link_full'     => $url.'?paged={p}',
        'link_first'    => $url              
    );	        
    $paging->init($config); 
?>
    <div class="tablenav top nbdesigner-pagination-con">
        <div class="tablenav-pages">
            <span class="displaying-num"><?php printf( _n( '%s Template', '%s Templates', $total, 'nbdesigner' ), number_format_i18n( $total ) ); ?>
            <?php echo $paging->html();  ?>
        </div>
    </div>  
<?php endif; ?>
