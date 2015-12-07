<form role="search" method="get" id="searchform" style="float: right;" action="<?php echo home_url( '/' ); ?>">
    <input type="text" name="s" id="s" value="Enter keywords ..." onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/><br />
    <input type="hidden" name="post_type" value="directory" />
    <input type="submit" id="searchsubmit" value="Search" />
</form>