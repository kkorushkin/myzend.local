<div class="box-box-box">Sort:&nbsp;
    <span id="sort-info"><?=$this->sort_type;?>
    </span><span id="sort-info"><?=$this->sort_price;?></span></div>
<?php foreach ($collection as $item) : //var_dump($collection)
    $i++;
    ?>
    <!--  -->
    <div id="tableGoods">
        <table class="coll-table" data-num="<?=$item->item_id;?>">
            <tr>
                <td colspan="2">
                    <p class="zf-midnightblue"><?php echo $this->escapeHtml($item->item_name) ;?></p>
                </td>
            </tr>
            <tr>
                <td class="collection-img-wrapper" colspan="2" style="background-image: url(<?php echo  Collection\Model\Collection::COLL_IMG_PATH.$this->escapeHtml($item->img_link);?>)">
                    <a href="<?php echo $this->url('collection', array('action'=>'item-details', 'id' => $item->item_id));?>">
                        <div class="goodsTable-price"><?php echo $item->item_price ;?> $</div>
                        <div class="goodsTable-price-holder"></div>
                    </a>
                </td>
            </tr>
            <tr class="mast-have-height">
                <td>
                    <!-- in case of ordinary get url request will need
                        <span class="zf-midnightblue"><a href="<?php echo $this->url('collection',
                        array('action'=>'item-details', 'id' => $item->item_id));?>">details</a></span>
                            -->
                </td>
                <td align="right">
                    <!-- in case of ordinary get url request will need -->
                    <!--
                        <span class="zf-midnightblue"><a href="<?php echo $this->url('collection',
                        array('action'=>'item-details', 'id' => $item->item_id));?>">to cart</a></span>
                        -->
                    <div id="this-to-cart-<?=$item->item_id;?>" class="to-cart-this" alt="[ to cart ]" data-num="<?=$item->item_id;?>"></div>
                </td>
            </tr>
        </table>
    </div>
<?php endforeach; ?>
