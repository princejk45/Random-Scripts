<?php
/*  ----------------------------------------------------------------------------
    Newspaper V9.0+ Child theme - Please do not use this child theme with older versions of Newspaper Theme

    What can be overwritten via the child theme:
     - everything from /parts folder
     - all the loops (loop.php loop-single-1.php) etc
	 - please read the child theme documentation: http://forum.tagdiv.com/the-child-theme-support-tutorial/

 */

/*  ----------------------------------------------------------------------------
    add the parent style + style.css from this folder
*/

/* Disabilita gli aggiornamenti automatici dei plugin di WordPress: */
add_filter( 'auto_update_plugin', '__return_false' );

/* Disabilita gli aggiornamenti automatici del tema WordPress: */
add_filter( 'auto_update_theme', '__return_false' );

/*inserisce video Sky a centro post */
function ri_adv_middle_content( $content ) {
    if( !is_single() )
        return $content;

/*$category = get_queried_object();
$cat_id = $category->term_id;*/

    $content = explode ( "</p>", $content );
    $paragraphAfter = count ( $content ) / 2 ;
    $paragraphAfter = (int)$paragraphAfter;
    $new_content = '';
        for ( $i = 0; $i < count ( $content ); $i ++ ) {
            if ( $i == $paragraphAfter) {
                $new_content .= '<div id="ri_adv_middle_content">';

               if (in_category('72')){ // Italia
                   $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17414" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
<script async src=“//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';

                }
                else if (in_category('77')){ //Calabria
                   $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17413" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
<script async src=“//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
                }
                else if (in_category('103')){ //Provincia
                   $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17412" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
<script async src=“//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
                }
                else if (in_category('66')){ //Area Urbana
                   $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17411" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
<script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
                }
                else{
                $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="8995" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
<script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
}
                $new_content .= '</div>';
             }
        $new_content .= $content[$i] . "</p>";
        }
return $new_content;
}

//add_filter( 'the_content', 'ri_adv_middle_content' );


add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles', 11);
function theme_enqueue_styles() {
    wp_enqueue_style('td-theme', get_template_directory_uri() . '/style.css', '', TD_THEME_VERSION, 'all' );
    wp_enqueue_style('td-theme-child', get_stylesheet_directory_uri() . '/style.css', array('td-theme'), TD_THEME_VERSION . 'c', 'all' );

}

/* check if user is administrator - NASCONDE PAGINE IN Backoffice se non è amministratore*/
add_action( 'admin_init', 'nh_remove_menu_pages' );
function nh_remove_menu_pages() {
    global $user_ID;
    //if the user is NOT an administrator remove the menu
    if ( !current_user_can( 'administrator' ) ) { //change role or capability here
        remove_menu_page( 'tdb_cloud_templates' ); //change menu item here
        remove_menu_page( 'wpseo_workouts' ); //change menu item here
        remove_menu_page( 'edit.php?post_type=tdc-review' ); //change menu item here
        remove_menu_page( 'tools.php' ); //change menu item here
        remove_menu_page( 'wpcf7' ); //change menu item here
        remove_meta_box('dashboard_primary', 'dashboard', 'normal'); //Removes the 'incoming links' widget
    }
}

function gp_rileva_device(){
    // Check if the "mobile" word exists in User-Agent 
    $isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile")); 
    
    // Check if the "tablet" word exists in User-Agent 
    $isTab = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "tablet")); 
    
    // Platform check  
    $isWin = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "windows")); 
    $isAndroid = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "android")); 
    $isIPhone = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "iphone")); 
    $isIPad = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "ipad")); 
    $isIOS = $isIPhone || $isIPad; 
    
    if($isMob){ 
        if($isTab){ 
            $device = 'Using Tablet Device...'; 
        }else{ 
            $device = 'Using Mobile Device...';
            
        } 
    }else{ 
        $device = 'Using Desktop...';
        //$banner = adrotate_group(34); 
    } 
    
    /*
    if($isIOS){ 
        $device 'iOS'; 
    }elseif($isAndroid){ 
        $device 'ANDROID'; 
    }elseif($isWin){ 
        $device 'WINDOWS'; 
    }
    */

    return $device;
}
//add_shortcode('gp_return_device', 'gp_rileva_device');    

// function that runs when shortcode is called
//Ritorna il riassunto in single cluod template per post ID
function gp_expert() { 
    $percorso = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url_path = parse_url($percorso,PHP_URL_PATH);
    $slug = pathinfo($url_path,PATHINFO_BASENAME);
    
    if ( $post = get_page_by_path( $slug, OBJECT, 'post' ) ){
        $id = $post->ID;
    }

    $postattivo = get_post($id);
    $expert = $postattivo->post_excerpt;

    // Output needs to be return
    return $expert;
}
// register shortcode
add_shortcode('gp_return_expert', 'gp_expert');

//Ritorna i banner in leaderboard Categoria
//Es.: Category Banner [IONIO] PIU' singolo articolo_1920x400
//nel template Categoria Newspaper
//
function gp_banner_lead_cat() {
    $percorso = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url_path = parse_url($percorso,PHP_URL_PATH);
    $slug = pathinfo($url_path,PATHINFO_BASENAME);

    switch ($slug) {
        case "area-urbana":
            $expert = adrotate_group(4);
            break;
        case "provincia":
            $expert = adrotate_group(19);
            break;
        case "ionio":
            $expert = adrotate_group(35);
            break;
        case "tirreno":
            $expert = adrotate_group(36);
            break;
        case "calabria":
            $expert = adrotate_group(37);
            break;
        case "italia":
            $expert = adrotate_group(38);
            break;     
        case "sport":
            $expert = adrotate_group(39);
            break;
        case "universita":
            $expert = adrotate_group(40);
            break;
        case "magazine":
            $expert = adrotate_group(41);
            break;
        default:
            //$expert = '<span style="font-size:8px">- Spazio pubblicità -</span>';
            $expert = adrotate_group(21);
            break;    
        }

    //if ('area-urbana' == $slug){
    //    $expert = adrotate_group(4);
    //}

    // Output needs to be return
    return $expert;
}
add_shortcode('gp_adrotate_leaderboard_categoria', 'gp_banner_lead_cat');

//Ritorna i banner in sidebar box Locale ad ROtate in Categorie 
function gp_banner_box_cat() {
    $percorso = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url_path = parse_url($percorso,PHP_URL_PATH);
    $slug = pathinfo($url_path,PATHINFO_BASENAME);
    switch ($slug ) {
    case "provincia":
        //Box-Sidebar-Provincia 
        $expert = adrotate_group(5);
        break;
    case "area-urbana":
        //Box-Sidebar-Area Urbana
        $expert = adrotate_group(6);
        break;
    case "green":
        echo "Your favorite color is green!";
        break;
    case "ionio":
        //Box-Sidebar-Ionio 
        $expert = adrotate_group(8);
        break;
    case "tirreno":
        //Box-Sidebar-Tirreno 
        $expert = adrotate_group(9);
        break;
    case "calabria":
        //Box-Sidebar-Calabria
        $expert = adrotate_group(10);
        break;                  
    case "italia":
            //Box-Sidebar-Italia
            $expert = adrotate_group(11);
            break;
    case "sport":
            //Box-Sidebar-Sport
            $expert = adrotate_group(12);
            break;
    case "universita":
            //Box-Sidebar-Universita
            $expert = adrotate_group(13);
            break;
    case "meteo":
            //Box-Sidebar-Meteo
            $expert = adrotate_group(14);
            break;
    case "magazine":
            //Box-Sidebar-Magazine
            $expert = adrotate_group(15);
            break;
    case "redazione":
         //Box-Sidebar-Area Urbana
            $expert = adrotate_group(6);
            break;
    case "pubblicita":
         //Box-Sidebar-Area Urbana
           $expert = adrotate_group(6);
           break;                   
    default:
        //Box-Sidebar-Default
        $expert = adrotate_group(7);
    }

    // Output needs to be return
    return $expert;

}
add_shortcode('gp_adrotate_box_categoria', 'gp_banner_box_cat');

function gp_box_articolo() { 
    $percorso = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $url_path = parse_url($percorso,PHP_URL_PATH);
    $slug = pathinfo($url_path,PATHINFO_BASENAME);
    
    if ( $post = get_page_by_path( $slug, OBJECT, 'post' ) ){
        $id = $post->ID;

        //$id_categoria = in_category('72');
        $categoria = get_the_category($post->ID);

        if ( ! empty( $categoria ) ) {
            $categoria_articolo = esc_html( $categoria[0]->slug );	
            switch ($categoria_articolo ) {
                case "provincia":
                    //Box-Sidebar-Articolo-provincia
                    $expert = adrotate_group(24);
                    break;
                case "area-urbana":
                    //Box-Sidebar-Articolo-Area Urbana
                    $expert = adrotate_group(25);
                    break;
                case "ionio":
                    //Box-Sidebar-Articolo-Ionio 
                    $expert = adrotate_group(26);
                    break;
                case "tirreno":
                    //Box-Sidebar-Articolo-Tirreno 
                    $expert = adrotate_group(27);
                    break;
                case "calabria":
                    //Box-Sidebar-Articolo-Calabria
                    $expert = adrotate_group(28);
                    break;                  
                case "italia":
                    //Box-Sidebar-Articolo-Italia
                    $expert = adrotate_group(30);
                    break;
                case "sport":
                    //Box-Sidebar-Articolo-Sport
                    $expert = adrotate_group(29);
                    break;
                case "universita":
                    //Box-Sidebar-Articolo-Universita
                    $expert = adrotate_group(31);
                    break;
                case "meteo":
                    //Box-Sidebar-Articolo-Meteo
                    $expert = adrotate_group(32);
                    break;
                case "magazine":
                    //Box-Sidebar-Articolo-Magazine
                    $expert = adrotate_group(33);
                    break;
                default:
                    //Box-Sidebar-Default
                    $expert = adrotate_group(7);
            }

        }
    }
    // Output needs to be return
    return $expert;
}
// register shortcode
add_shortcode('gp_adrotate_box_articolo', 'gp_box_articolo');

//Locale ad ROtate in Categorie LeaderBoard
function gp_banner_leaderboard_cat() {
    // Output needs to be return leaderboard Categorie
    return adrotate_group(4);
}
add_shortcode('gp_leaderboard_cat', 'gp_banner_leaderboard_cat');


//Sky in Post Singolo
function gp_single_content() {
    global $post;

    //$id_categoria = in_category('72');
    $categoria = get_the_category($post->ID);

    if ( ! empty( $categoria ) ) {
        $categoria_corrente = esc_html( $categoria[0]->slug );	
    }

    switch ($categoria_corrente) {
        case "provincia":
            //Box-Sidebar-Provincia 
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17412" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                            <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center> ';
            break;
        case "area-urbana":
            //Box-Sidebar-Area Urbana
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17411" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                            <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>' ;
				
            break;
        case "calabria":
            //Sky 
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17413" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                            <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
            break;                  
        case "italia":
            //Italia
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17414" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                        <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
            break;
        default:
            //Default
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="8995" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                        <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
        }

    // Output needs to be return leaderboard Categorie
    return $new_content;
}
add_shortcode('gp_banner_single_content', 'gp_single_content');

function gp_video_sky() {
    global $post;

    //$id_categoria = in_category('72');
    $categoria = get_the_category($post->ID);

    if ( ! empty( $categoria ) ) {
        $categoria_corrente = esc_html( $categoria[0]->slug );	
    }

    switch ($categoria_corrente) {
        case "provincia":
            //Box-Sidebar-Provincia 
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17412" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                            <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
            break;
        case "area-urbana":
            //Box-Sidebar-Area Urbana
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17411" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                            <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
            break;
        case "calabria":
            //Sky 
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17413" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                            <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
            break;                  
        case "italia":
            //Italia
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="17414" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                        <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
            break;
        default:
            //Default
            $new_content .= /*'[smart_post_show id="414485"]';*/ '<center><ins data-fluid-zoneid="8995" data-fluid-id="e6404a5432b1988ea2e71ec092e8608a"></ins>
                        <script async src="//fluid.4strokemedia.com/www/fluid/player.php"></script></center>';
        }

    // Output needs to be return leaderboard Categorie
    return $new_content;
}
add_shortcode('video_sky', 'gp_video_sky');

//336x280 Smart mobile inside article
function gp_single_content_clickio() {
    
    $new_clickio = "<script async type='text/javascript' src='//s.clickiocdn.com/t/220109/360_light.js'>
    </script> <script class='__lxGc__' type='text/javascript'> ((__lxGc__=window.__lxGc__||{'s':{},'b':0})['s']['_220109']=__lxGc__['s']['_220109']||{'b':{}})['b']['_676544']={'i':__lxGc__.b++}; </script>";

    // Output needs to be return leaderboard Categorie
    return $new_clickio;
}
add_shortcode('gp_banner_single_content_clickio', 'gp_single_content_clickio');


//*************** */ Funzioni per APP *************************************//

//giancarlo prisco #--------- Funzione di creazione

	// Logica di aggiornamento messaggio
	// ----------------------------------
	// 1 Settato  in         ---> function elimina_da_app (function.php)
	// 2 Stampato in tabella ---> function custom_columns (function.php)  
	// 2 Stampato in form    ---> edit-form-advanced.php  (edit-form-advanced.php)
	// 3 UNSET    in         ---> function pubblica_in_app (function.php)

	//add_action( admin_post_$hook_name:string, $callback:callable, $priority:integer, $accepted_args:integer )
	add_action('admin_post_crea_post_in_app','funz_crea_post');
	function funz_crea_post(){
		if (isset($_POST['pubblica_in_app'])){

			if (isset($_POST['post_id'])){

				$_SESSION['post_id'] = $_POST['post_id'];

				$post_ID = $_SESSION['post_id'];
				$post = get_post($post_ID);

				pubblica_in_app($post_ID,$post,true);
			}	
		}
		wp_redirect('https://quicosenza.it/news/wp-admin/edit.php');
		exit;
	}


function pubblica_in_app($post_ID, $post = null, $update = true) {
		
    if ($post->post_type == 'post'){

        // Se è stato premuto il button Pubblica/Aggiorna in APP
        unset($_SESSION['aggiorna_in_app']);
        unset($_SESSION['aggiorna_in_app_idpost']);
        
    
        //Se e vuoto cc_tipo_post
        $cc_tipo_post = get_post_meta($post_ID,'cc_tipo_post',true);
        if (empty($cc_tipo_post)){

            update_post_meta($post_ID,'cc_tipo_post','auto-draft');
            $_SESSION['post_status'] = "auto-draft";
            $_SESSION['reload'] = 'no';
            
            //wp_redirect('https://qui.calabriaunited.com/wp-admin/edit.php');
            //exit;
            //die();
        }

        //Pubblica - Salva bozza - Pianifica procedo
        if($post->post_status <> 'auto-draft'){

            //Cerco i dati 
                //Cerco l' autore
                $author_id = get_post_field ('post_author', $post_ID);
                $nome_autore = get_the_author_meta( 'nickname' , $author_id ); 
                echo 'Autore:'.$nome_autore.'<br>';
                
                //valorizzo le variabili per json array
                $titolo = $post->post_title;
                //$content = wp_strip_all_tags($post_after->post_content);
                $content = $post->post_content;

                // Per il tema newspaper richiamo il subtitle
	            $id = $post->ID;
            	$meta_sottotitolo = get_post_meta($id,'td_post_theme_settings',true);
	            $riassunto = $meta_sottotitolo['td_subtitle'];
                // $riassunto = $post->post_excerpt;
                



                //full - medium
                $url = wp_get_attachment_url( get_post_thumbnail_id($post_ID), 'thumbnail' );
                echo 'Titolo1: '.$titolo.'<br>';
                echo 'Riassunto: '.$riassunto.'<br>';
                echo 'Url: '.$url.'<br>';
                echo '<img src="'.$url.'" width="100" height="100" /><br>';

                $categories = get_the_terms($post_ID, 'category');
                //print_r($categories);
                
                    foreach($categories as $value) {
                        $cat = $value->slug;
                        $array_lista_categorie[] = $cat;
                        echo 'Categoria: '.$cat.'<br>';
                    }

                //CPT VIDEO
                $video = get_post_meta($post_ID,'cc_url_video_quicosenza',true);
                $video_youtube = get_post_meta($post_ID,'cc_url_video_youtube',true);

                $invia_notifica_app = get_post_meta($post_ID,'invia_notifica_app',true);
                echo 'Video: '.$video.'<br>';
                echo '<hr>';	

                //Controllo se il post e Nuovo prendendo il campo cc_tipo_post
                $cc_id_post = get_post_meta($post_ID,'cc_id_post',true);
                $cc_tipo_post = get_post_meta($post_ID,'cc_tipo_post',true);

                echo 'Post Satus:'.$post->post_status.'<br>';
                echo 'cc_tipo_post'.$cc_tipo_post.'<br>';
            //Fine cerco i dati	

            //BUTTON AGGIUNGI ARTICOLO - Nuovo post sicuramente
            if ($cc_tipo_post == 'auto-draft'){ 

                //Salvato come BOZZA
                if($post->post_status == 'draft'){

                    echo '<h4>DRAFT</h4>';
                    update_post_meta($post_ID,'cc_tipo_post','draft');

                    $_SESSION['post_status'] = "draft";
                    $_SESSION['reload'] = 'no';
                    $_SESSION['pubblicato'] = "Non pubblicato";

                    wp_redirect('https://quicosenzastage.lafull.it/news/wp-admin/edit.php');
                    exit;
                    
                }

                $cc = get_post_meta($post_ID,'cc_tipo_post',true);
                echo $cc.'-'.$post->post_status;

                if ($post->post_status <> 'draft'){

                    //Da GIA' Programmato a publish //non serve
                    if ($cc == 'future' && $post->post_status == 'publish'){
                        update_post_meta($post_ID,'cc_tipo_post','publish');

                        $_SESSION['post_status'] = "publish";
                        $_SESSION['reload'] = 'no';
                        
                        wp_redirect('https://quicosenzastage.lafull.it/news/wp-admin/edit.php');
                        exit;
                    }

                    //NUOVA auto-draft a publish con Notifica FAtta con youtube
                    if ($cc == 'auto-draft' && $post->post_status == 'publish'){
                        //--- NEW Post - POST	
                        echo '<h4>NUOVO->Publish</h4>';
                        update_post_meta($post_ID,'cc_tipo_post','publish');

                        $_SESSION['post_status'] = "publish";
                        $_SESSION['reload'] = 'si';
                        $_SESSION['pubblicato'] = "Pubblicato";

                        ?>
                            <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                            <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                            <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
                            <textarea id="contenuto" type="text">
                            <?php echo $content; ?>
                            </textarea>
                            <input id="url" type="text" value="<?php echo $url; ?>"/>
                            <input id="video" type="text" value="<?php echo $video; ?>"/>
                            <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                            
                            <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                            <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                            
                            <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                            <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                            <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                            <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                            <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>
                            <script>
                                var id          = document.getElementById('postid').value;
                                var titolo      = document.getElementById('titolo').value;
                                var riassunto   = document.getElementById('riassunto').value;
                                var contenuto   = document.getElementById('contenuto').value;
                                var url         = document.getElementById('url').value;
                                var video       = document.getElementById('video').value;
                                var video_youtube  = document.getElementById('video_youtube').value;

                                var nome_autore = document.getElementById('nome_autore').value;
                                var invia_notifica_app = document.getElementById('invia_notifica_app').value;

                                //var DataProgrammazione = document.getElementById('data_programmazione').value;
                            
                                var cat0 = document.getElementById('cat0').value;
                                var cat1 = document.getElementById('cat1').value;
                                var cat2 = document.getElementById('cat2').value;
                                var cat3 = document.getElementById('cat3').value;
                                var cat4 = document.getElementById('cat4').value;

                                let allCatToArray = [];
                                if (cat0){
                                    allCatToArray.push(cat0);
                                }
                                if (cat1){
                                    allCatToArray.push(cat1);
                                }
                                if (cat2){
                                    allCatToArray.push(cat2);
                                }
                                if (cat3){
                                    allCatToArray.push(cat3);
                                }
                                if (cat4){
                                    allCatToArray.push(cat4);
                                }

                                if (typeof(id) != 'undefined' && id != null) {
                                    var id  = document.getElementById('postid').value;
                                    //alert('Id: '+id);
                                }

                                if (typeof(riassunto) != 'undefined' && riassunto != null) {
                                    var riassunto  = document.getElementById('riassunto').value;
                                    //alert('Riassunto: '+riassunto);
                                }

                                if (typeof(contenuto) != 'undefined' && contenuto != null) {
                                    var contenuto  = document.getElementById('contenuto').value;
                                    //alert('Contenuto: '+contenuto);
                                }

                                if (typeof(url) != 'undefined' && url != null) {
                                    var url  = document.getElementById('url').value;
                                    //alert('Url: '+url);
                                }

                                if (typeof(video) != 'undefined' && video != null) {
                                    var video  = document.getElementById('video').value;
                                    //alert('Video: '+video);
                                }

                                if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                    var video_youtube  = document.getElementById('video_youtube').value;
                                    alert('Video_youtube: '+video_youtube);
                                }

                                if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                                    var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                                    alert('invia_notifica_app: '+invia_notifica_app);
                                }

                                if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                                    var nome_autore  = document.getElementById('nome_autore').value;
                                    alert('nome_autore: '+nome_autore);
                                }
                                
                                //#	----------------------------------------- Nuova Notifica POST NEWS--------------------------------------------------
                                /*
                                async function nuovaNotificaPostNews() {
                                        const request = await fetch("https://v1-api.lafull.it/api/notification/send", {
                                                        
                                            method: "POST",
                                            headers: {
                                                "content-type": "application/json",
                                            },
                                            body: JSON.stringify(
                                                {
                                                "title": titolo,
                                                "body" : riassunto,
                                                })
                                        })
                                    
                                    // Richiama la funzione della notifica
                                    const json = await request.json();
                                    
                                    if (json.success){
                                        alert("Notifica Inviata per nuovo POST 'auto-draft-publish': "+ json.status);
                                    }else{
                                        alert("Errore Notifica auto-draft-publish");	
                                    }
                                    
                                }
                                */

                                //#	----------------------------------------- Nuovo POST News--------------------------------------------------
                                
                                async function nuovoPostNews() {
                                        //alert ('Nuovo Post Inserito: "auto-draft-publish": '+id);
                                        
                                        const request = await fetch("https://v1-api.lafull.it/api/news/", {
                                                        
                                            method: "POST",
                                            headers: {
                                                "content-type": "application/json",
                                            },
                                            body: JSON.stringify(
                                                {
                                                "mySqlId"  : id,
                                                "title"    : titolo,
                                                "author"   : nome_autore,
                                                "subtitle" : riassunto,
                                                "field"    : contenuto,
                                                "category" : allCatToArray,
                                                "image"    : url,
                                                "video"    : video,
                                                "YTvideo"  : video_youtube,
                                                "notificationField" : invia_notifica_app,
                                                })
                                        })
                                        
                                    
                                    // Richiama la funzione della notifica
                                    const json = await request.json();

                                    if (json.success) {
                                        //await nuovaNotificaPostNews();
                                        alert("Nuovo POST auto-draft publish");
                                    }else{
                                        alert("Errore Post in APP non pubblicato-auto-draft-publish");
                                    }
                                                                        
                                    //var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                                    //window.location.assign(url_edit);
                                    history.back();
                                    
                                }
                            
                                nuovoPostNews();
                            
                            </script>
                        <?php
                    }	
                    
                    //NUOVA PROGRAMMATA auto-draft a future con Notifica FAtta con youtube
                    if($cc == 'auto-draft' && $post->post_status == 'future'){
                        echo '<h4>FUTURE</h4>';
                        update_post_meta($post_ID,'cc_tipo_post','future');

                        $_SESSION['post_status'] = "future";
                        $_SESSION['reload'] = 'si';
                        $_SESSION['pubblicato'] = "PROGRAMMATO";

                        echo 'Post Data programmazione: '.$post->post_date.'<br>';
                        $data_programmazione = $post->post_date;
                        $data_programmazione_unix = strtotime($post->post_date);
                        echo 'Data programmazione: '.$data_programmazione.'<br>';
                        echo '<h4>Post Programmato</h4>';
                        echo 'Unix Data $data_programmazione_unix;'.$data_programmazione_unix;
                        ?>
    
                        <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                        <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                        <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
                        
                        <textarea id="contenuto" type="text">
                        <?php echo $content; ?>
                        </textarea>
            
                        <input id="url" type="text" value="<?php echo $url; ?>"/>
                        <input id="video" type="text" value="<?php echo $video; ?>"/>
                        <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                        <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                        <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                        <input id="data_programmazione" type="text" value="<?php echo $data_programmazione_unix; ?>"/>
                        <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                        <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                        <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                        <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                        <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>

                        <script>
                            
                            var id          = document.getElementById('postid').value;
                            var titolo      = document.getElementById('titolo').value;
                            var riassunto   = document.getElementById('riassunto').value;
                            var contenuto   = document.getElementById('contenuto').value;
                            var url         = document.getElementById('url').value;
                            var video       = document.getElementById('video').value;
                            var video_youtube  = document.getElementById('video_youtube').value;
                            var nome_autore = document.getElementById('nome_autore').value;
                            var invia_notifica_app = document.getElementById('invia_notifica_app').value;
                            var DataProgrammazione = document.getElementById('data_programmazione').value;
                        
                            var cat0 = document.getElementById('cat0').value;
                            var cat1 = document.getElementById('cat1').value;
                            var cat2 = document.getElementById('cat2').value;
                            var cat3 = document.getElementById('cat3').value;
                            var cat4 = document.getElementById('cat4').value;
                            
                
                            let allCatToArray = [];
                            if (cat0){
                                allCatToArray.push(cat0);
                            }
                            if (cat1){
                                allCatToArray.push(cat1);
                            }
                            if (cat2){
                                allCatToArray.push(cat2);
                            }
                            if (cat3){
                                allCatToArray.push(cat3);
                            }
                            if (cat4){
                                allCatToArray.push(cat4);
                            }
                        
                
                            if (typeof(id) != 'undefined' && id != null) {
                                var id  = document.getElementById('postid').value;
                                //alert('Id: '+id);
                            }
                
                            if (typeof(riassunto) != 'undefined' && riassunto != null) {
                                var riassunto  = document.getElementById('riassunto').value;
                                //alert('Riassunto: '+riassunto);
                            }
                
                            if (typeof(contenuto) != 'undefined' && contenuto != null) {
                                var contenuto  = document.getElementById('contenuto').value;
                                //alert('Contenuto: '+contenuto);
                            }
                
                            if (typeof(url) != 'undefined' && url != null) {
                                var url  = document.getElementById('url').value;
                                //alert('Url: '+url);
                            }
                
                            if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                                var nome_autore  = document.getElementById('nome_autore').value;
                                //alert('nome_autore: '+nome_autore);
                            }
                
                            
                            if (typeof(DataProgrammazione) != 'undefined' && DataProgrammazione != null) {
                                var DataProgrammazione  = document.getElementById('data_programmazione').value;
                                //alert('data: '+DataProgrammazione);
                            }

                            if (typeof(video) != 'undefined' && video != null) {
                                    var video  = document.getElementById('video').value;
                                //alert('Video: '+video);
                            }

                            if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                    var video_youtube  = document.getElementById('video_youtube').value;
                                    alert('Video_youtube: '+video_youtube);
                                }

                            if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                                var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                                //alert('invia_notifica_app: '+invia_notifica_app);
                            }
                            
                            
                            async function nuovaNewsProgrammataAutodraftPublish() {
                                    //alert ('NUOVA Programmata funzione:'+id);
                                    //alert(typeof DataProgrammazione);
                                    const request = await fetch("https://v1-api.lafull.it/api/jobs?jobType=delayedJob&unixDate="+DataProgrammazione, {
                                                    
                                        method: "POST",
                                        headers: {
                                            "content-type": "application/json",
                                        },
                                        body: JSON.stringify(
                                            {
                                            "data" : 
                                                {
                                                "mySqlId"  : id,
                                                "title"    : titolo,
                                                "author"   : nome_autore,
                                                "subtitle" : riassunto,
                                                "field"    : contenuto,
                                                "category" : allCatToArray,
                                                "image"    : url,
                                                "video"    : video,
                                                "YTvideo"  : video_youtube,
                                                "notificationField" : invia_notifica_app,
                                                }	
                                            })
                                    })
                                
                                // Richiama la funzione della notifica
                                const json = await request.json();

                                if (json.success){
                                    alert("NUOVO POST PROGRAMMATA: autodraft-publish "+ json.message);
                                }else{
                                    alert("Errore Notifica AutodraftPublish: "+ json.message);	
                                }
                                                                
                                //var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                                //window.location.assign(url_edit);
                                history.back();
                                
                            }
                        
                            nuovaNewsProgrammataAutodraftPublish();
                
                        </script>
                        
                        <!--
                        //-----------------------------------------------------------------------------------------------
                        <body onload="document.myform.submit()">
                            <form action="https://qui-cosenza.herokuapp.com/api/notification/send" method="POST" name="myform">
                                <input type="text" name="title" value="Teest gp" >
                                <input id="test" type="submit" value="Submit">
                            </form>
                        </body>
                        //-----------------------------------------------------------------------------------------------
                        -->
                        <?php  
                    }
                    
                    die();

                }else{

                    //Salvato come BOZZA DA NUOVO - non fa niente
                    echo '<h4>DRAFT</h4>';
                    update_post_meta($post_ID,'cc_tipo_post','draft');

                    $_SESSION['post_status'] = "draft";
                    $_SESSION['reload'] = 'si';

                    wp_redirect('https://quicosenzastage.lafull.it/news/wp-admin/edit.php');
                    exit;
                    
                }

                //importante bloccare per javascript
                //die();	

            //BUTTON MODIFICA ARTICOLO -
            }else{ //post aggiornato e puo Essere --> Publish - Trash - Draft - Future
                
                $cc_tipo_post = get_post_meta($post_ID,'cc_tipo_post',true);
                echo 'cc_tipo_post: '.$cc_tipo_post.' Post Status: '.$post->post_status.'<br>';
                
                //Evento non richiamabile dentro questo hook
                if ($cc_tipo_post == 'future' && $post->post_status == 'publish'){
                    update_post_meta($post_ID,'cc_tipo_post','publish');
                    $_SESSION['post_status'] = "publish";
                    $_SESSION['reload'] = 'no-future-publish';
                    wp_redirect('https://quicosenzastage.lafull.it/news/wp-admin/edit.php');
                    exit;
                }

                //Da BOZZA A BOZZA - ritorna
                if($cc_tipo_post == 'draft' && $post->post_status == 'draft'){
                    update_post_meta($post_ID,'cc_tipo_post','draft');
                    $_SESSION['post_status'] = "draft";
                    $_SESSION['reload'] = 'no';
                    wp_redirect('https://quicosenzastage.lafull.it/news/wp-admin/edit.php');
                    exit;
                }

                //DA BOZZA A PROGRAMMATO - draft-future con Notifica Fatta con youtube
                if($cc_tipo_post == 'draft' && $post->post_status == 'future'){
                    echo '<h4>FUTURE</h4>';
                    update_post_meta($post_ID,'cc_tipo_post','future');

                    $_SESSION['post_status'] = "future";
                    $_SESSION['reload'] = 'si';
                    $_SESSION['pubblicato'] = "PROGRAMMATO";
                    

                    echo 'Post Data programmazione: '.$post->post_date.'<br>';
                    $data_programmazione = $post->post_date;
                    $data_programmazione_unix = strtotime($post->post_date);
                    echo 'Data programmazione: '.$data_programmazione.'<br>';
                    echo '<h4>Post Programmato</h4>';
                    echo 'Unix Data $data_programmazione_unix;'.$data_programmazione_unix;
                    ?>

                    <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                    <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                    <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
                    
                    <textarea id="contenuto" type="text">
                    <?php echo $content; ?>
                    </textarea>
        
                    <input id="url" type="text" value="<?php echo $url; ?>"/>
                    <input id="video" type="text" value="<?php echo $video; ?>"/>
                    <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                    <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                    <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                    <input id="data_programmazione" type="text" value="<?php echo $data_programmazione_unix; ?>"/>
                    <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                    <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                    <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                    <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                    <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>

                    <script>
                        
                        var id          = document.getElementById('postid').value;
                        var titolo      = document.getElementById('titolo').value;
                        var riassunto   = document.getElementById('riassunto').value;
                        var contenuto   = document.getElementById('contenuto').value;
                        var url         = document.getElementById('url').value;
                        var video       = document.getElementById('video').value;
                        var video_youtube  = document.getElementById('video_youtube').value;
                        var invia_notifica_app = document.getElementById('invia_notifica_app').value;
                        var nome_autore = document.getElementById('nome_autore').value;
                        var DataProgrammazione = document.getElementById('data_programmazione').value;
                    
                        var cat0 = document.getElementById('cat0').value;
                        var cat1 = document.getElementById('cat1').value;
                        var cat2 = document.getElementById('cat2').value;
                        var cat3 = document.getElementById('cat3').value;
                        var cat4 = document.getElementById('cat4').value;
                        
            
                        let allCatToArray = [];
                        if (cat0){
                            allCatToArray.push(cat0);
                        }
                        if (cat1){
                            allCatToArray.push(cat1);
                        }
                        if (cat2){
                            allCatToArray.push(cat2);
                        }
                        if (cat3){
                            allCatToArray.push(cat3);
                        }
                        if (cat4){
                            allCatToArray.push(cat4);
                        }
                    
            
                        if (typeof(id) != 'undefined' && id != null) {
                            var id  = document.getElementById('postid').value;
                            //alert('Id: '+id);
                        }
            
                        if (typeof(riassunto) != 'undefined' && riassunto != null) {
                            var riassunto  = document.getElementById('riassunto').value;
                            //alert('Riassunto: '+riassunto);
                        }
            
                        if (typeof(contenuto) != 'undefined' && contenuto != null) {
                            var contenuto  = document.getElementById('contenuto').value;
                            //alert('Contenuto: '+contenuto);
                        }
            
                        if (typeof(url) != 'undefined' && url != null) {
                            var url  = document.getElementById('url').value;
                            //alert('Url: '+url);
                        }
            
                        if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                            var nome_autore  = document.getElementById('nome_autore').value;
                            //alert('nome_autore: '+nome_autore);
                        }
            
                        
                        if (typeof(DataProgrammazione) != 'undefined' && DataProgrammazione != null) {
                            var DataProgrammazione  = document.getElementById('data_programmazione').value;
                            //alert('data: '+DataProgrammazione);
                        }

                        if (typeof(video) != 'undefined' && video != null) {
                                var video  = document.getElementById('video').value;
                            //alert('Video: '+video);
                        }

                        if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                var video_youtube  = document.getElementById('video_youtube').value;
                                alert('Video_youtube: '+video_youtube);
                            }

                        if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                            var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                            alert('AGGIORNAMENTO-invia_notifica_app: '+invia_notifica_app);
                        }
                    
                        
                        //-----------------------------------------NON VIENE CHIAMATA-------------------------------------------------
                        /*
                        async function notificaNews() {
                                const request = await fetch("https://v1-api.lafull.it/api/notification/send", {
                                                
                                    method: "POST",
                                    headers: {
                                        "content-type": "application/json",
                                    },
                                    body: JSON.stringify(
                                        {
                                        "title": titolo,
                                        "body" : riassunto,
                                        })
                                })
                            
                            // Richiama la funzione della notifica
                            const json = await request.json();
                            
                            
                            if (json.status){
                                alert("NUOVA Notifica PROGRAMMATA inviata: "+ json.status);
                            }else{
                                alert("Errore Notifica");	
                            }
                            
                            //window.location = url_edit+'?post='+parseInt(id)+'&action=edit';
                            //history.back();
                        }
                        */
                        
                        async function nuovaNewsProgrammataDabozzaFuture() {
                                //alert ('NUOVO POST Programmato da draft:'+id);
                                //alert(typeof DataProgrammazione);
                                const request = await fetch("https://v1-api.lafull.it/api/jobs?jobType=delayedJob&unixDate="+DataProgrammazione, {
                                                
                                    method: "POST",
                                    headers: {
                                        "content-type": "application/json",
                                    },
                                    body: JSON.stringify(
                                        {
                                        "data" : 
                                            {
                                            "mySqlId"  : id,
                                            "title"    : titolo,
                                            "author"   : nome_autore,
                                            "subtitle" : riassunto,
                                            "field"    : contenuto,
                                            "category" : allCatToArray,
                                            "image"    : url,
                                            "video"    : video,
                                            "YTvideo"  : video_youtube,
                                            "notificationField" : invia_notifica_app,
                                            }	
                                        })
                                })
                            
                            // Richiama la funzione della notifica
                            const json = await request.json();

                            if (json.success){
                                alert("NUOVO POST PROGRAMMATO DA draft: "+ json.message);
                            }else{
                                alert("Errore Notifica AutodraftPublish: "+ json.message);	
                            }
                                                            
                            // var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                            // window.location.assign(url_edit);
                            history.back();
                            
                        }
                    
                        nuovaNewsProgrammataDabozzaFuture();
            
                    </script>
                    <?php  
                }

                //DA gia pubblicato a riprogrammato con Notifica Fatta con youtube
                if($cc_tipo_post == 'publish' && $post->post_status == 'future'){
                    echo '<h4>FUTURE</h4>';
                    update_post_meta($post_ID,'cc_tipo_post','future');

                    $_SESSION['post_status'] = "future";
                    $_SESSION['reload'] = 'si';
                    $_SESSION['pubblicato'] = "PROGRAMMATO";
                    

                    echo 'Post Data programmazione: '.$post->post_date.'<br>';
                    $data_programmazione = $post->post_date;
                    $data_programmazione_unix = strtotime($post->post_date);
                    echo 'Data programmazione: '.$data_programmazione.'<br>';
                    echo '<h4>Post Programmato</h4>';
                    echo 'Unix Data $data_programmazione_unix;'.$data_programmazione_unix;
                    ?>

                    <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                    <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                    <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
                    
                    <textarea id="contenuto" type="text">
                    <?php echo $content; ?>
                    </textarea>
        
                    <input id="url" type="text" value="<?php echo $url; ?>"/>
                    <input id="video" type="text" value="<?php echo $video; ?>"/>
                    <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                    <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                    <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                    <input id="data_programmazione" type="text" value="<?php echo $data_programmazione_unix; ?>"/>
                    <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                    <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                    <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                    <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                    <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>

                    <script>
                        
                        var id          = document.getElementById('postid').value;
                        var titolo      = document.getElementById('titolo').value;
                        var riassunto   = document.getElementById('riassunto').value;
                        var contenuto   = document.getElementById('contenuto').value;
                        var url         = document.getElementById('url').value;
                        var video       = document.getElementById('video').value;
                        var video_youtube  = document.getElementById('video_youtube').value;
                        var invia_notifica_app = document.getElementById('invia_notifica_app').value;
                        var nome_autore = document.getElementById('nome_autore').value;
                        var DataProgrammazione = document.getElementById('data_programmazione').value;
                    
                        var cat0 = document.getElementById('cat0').value;
                        var cat1 = document.getElementById('cat1').value;
                        var cat2 = document.getElementById('cat2').value;
                        var cat3 = document.getElementById('cat3').value;
                        var cat4 = document.getElementById('cat4').value;
                        
            
                        let allCatToArray = [];
                        if (cat0){
                            allCatToArray.push(cat0);
                        }
                        if (cat1){
                            allCatToArray.push(cat1);
                        }
                        if (cat2){
                            allCatToArray.push(cat2);
                        }
                        if (cat3){
                            allCatToArray.push(cat3);
                        }
                        if (cat4){
                            allCatToArray.push(cat4);
                        }
                    
            
                        if (typeof(id) != 'undefined' && id != null) {
                            var id  = document.getElementById('postid').value;
                            //alert('Id: '+id);
                        }
            
                        if (typeof(riassunto) != 'undefined' && riassunto != null) {
                            var riassunto  = document.getElementById('riassunto').value;
                            //alert('Riassunto: '+riassunto);
                        }
            
                        if (typeof(contenuto) != 'undefined' && contenuto != null) {
                            var contenuto  = document.getElementById('contenuto').value;
                            //alert('Contenuto: '+contenuto);
                        }
            
                        if (typeof(url) != 'undefined' && url != null) {
                            var url  = document.getElementById('url').value;
                            //alert('Url: '+url);
                        }
            
                        if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                            var nome_autore  = document.getElementById('nome_autore').value;
                            //alert('nome_autore: '+nome_autore);
                        }
            
                        
                        if (typeof(DataProgrammazione) != 'undefined' && DataProgrammazione != null) {
                            var DataProgrammazione  = document.getElementById('data_programmazione').value;
                            alert('data: '+DataProgrammazione);
                        }

                        if (typeof(video) != 'undefined' && video != null) {
                                var video  = document.getElementById('video').value;
                            //alert('Video: '+video);
                        }

                        if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                var video_youtube  = document.getElementById('video_youtube').value;
                                alert('Video_youtube: '+video_youtube);
                        }

                        if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                            var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                            alert('AGGIORNAMENTO-invia_notifica_app: '+invia_notifica_app);
                        }
                    
                        
                        //-----------------------------------------NON VIENE CHIAMATA-------------------------------------------------
                        /*
                        async function notificaNews() {
                                const request = await fetch("https://v1-api.lafull.it/api/notification/send", {
                                                
                                    method: "POST",
                                    headers: {
                                        "content-type": "application/json",
                                    },
                                    body: JSON.stringify(
                                        {
                                        "title": titolo,
                                        "body" : riassunto,
                                        })
                                })
                            
                            // Richiama la funzione della notifica
                            const json = await request.json();
                            
                            
                            if (json.status){
                                alert("NUOVA Notifica PROGRAMMATA inviata: "+ json.status);
                            }else{
                                alert("Errore Notifica");	
                            }
                            
                            //window.location = url_edit+'?post='+parseInt(id)+'&action=edit';
                            //history.back();
                        }
                        */
                        
                        async function nuovaNewsPublishFuture() {
                                //alert ('NUOVO POST Publish-Future:'+id);
                                //alert(typeof DataProgrammazione);
                                const request = await fetch("https://v1-api.lafull.it/api/jobs?jobType=delayedJob&unixDate="+DataProgrammazione, {
                                                
                                    method: "POST",
                                    headers: {
                                        "content-type": "application/json",
                                    },
                                    body: JSON.stringify(
                                        {
                                        "data" : 
                                            {
                                            "mySqlId"  : id,
                                            "title"    : titolo,
                                            "author"   : nome_autore,
                                            "subtitle" : riassunto,
                                            "field"    : contenuto,
                                            "category" : allCatToArray,
                                            "image"    : url,
                                            "video"    : video,
                                            "YTvideo"  : video_youtube,
                                            "notificationField" : invia_notifica_app,
                                            }	
                                        })
                                })
                            
                            // Richiama la funzione della notifica
                            const json = await request.json();

                            if (json.success){
                                alert("NUOVO POST PROGRAMMATO DA draft: "+ json.message);
                            }else{
                                alert("Errore Notifica AutodraftPublish: "+ json.message);	
                            }
                                                            
                            // var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                            // window.location.assign(url_edit);
                            history.back();
                            
                        }
                    
                        nuovaNewsPublishFuture();
            
                    </script>
                    <?php 
                }	
                
                //DA BOZZA A PUBBLICATO - draft-publish con Notifica FAtta con youtube
                if ($cc_tipo_post == 'draft' && $post->post_status == 'publish'){
                    
                        echo '<h4>BOZZA -> Publish</h4>';
                        update_post_meta($post_ID,'cc_tipo_post','publish');

                        $_SESSION['post_status'] = "publish";
                        $_SESSION['reload'] = 'si';
                        $_SESSION['pubblicato'] = "PUBBLICATO";

                        ?>
                            <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                            <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                            <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
                            <textarea id="contenuto" type="text">
                            <?php echo $content; ?>
                            </textarea>
                            <input id="url" type="text" value="<?php echo $url; ?>"/>
                            <input id="video" type="text" value="<?php echo $video; ?>"/>
                            <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                            <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                            <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                            
                            <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                            <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                            <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                            <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                            <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>
                            <script>
                                var id          = document.getElementById('postid').value;
                                var titolo      = document.getElementById('titolo').value;
                                var riassunto   = document.getElementById('riassunto').value;
                                var contenuto   = document.getElementById('contenuto').value;
                                var url         = document.getElementById('url').value;
                                var video       = document.getElementById('video').value;
                                var video_youtube  = document.getElementById('video_youtube').value;
                                var invia_notifica_app = document.getElementById('invia_notifica_app').value;
                                var nome_autore = document.getElementById('nome_autore').value;
                                //var DataProgrammazione = document.getElementById('data_programmazione').value;
                            
                                var cat0 = document.getElementById('cat0').value;
                                var cat1 = document.getElementById('cat1').value;
                                var cat2 = document.getElementById('cat2').value;
                                var cat3 = document.getElementById('cat3').value;
                                var cat4 = document.getElementById('cat4').value;

                                let allCatToArray = [];
                                if (cat0){
                                    allCatToArray.push(cat0);
                                }
                                if (cat1){
                                    allCatToArray.push(cat1);
                                }
                                if (cat2){
                                    allCatToArray.push(cat2);
                                }
                                if (cat3){
                                    allCatToArray.push(cat3);
                                }
                                if (cat4){
                                    allCatToArray.push(cat4);
                                }

                                if (typeof(id) != 'undefined' && id != null) {
                                    var id  = document.getElementById('postid').value;
                                    //alert('Id: '+id);
                                }

                                if (typeof(riassunto) != 'undefined' && riassunto != null) {
                                    var riassunto  = document.getElementById('riassunto').value;
                                    //alert('Riassunto: '+riassunto);
                                }

                                if (typeof(contenuto) != 'undefined' && contenuto != null) {
                                    var contenuto  = document.getElementById('contenuto').value;
                                    //alert('Contenuto: '+contenuto);
                                }

                                if (typeof(url) != 'undefined' && url != null) {
                                    var url  = document.getElementById('url').value;
                                    //alert('Url: '+url);
                                }

                                if (typeof(video) != 'undefined' && video != null) {
                                    var video  = document.getElementById('video').value;
                                    //alert('Video: '+video);
                                }

                                if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                    var video_youtube  = document.getElementById('video_youtube').value;
                                    alert('Video_youtube: '+video_youtube);
                                }

                                

                                if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                                    var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                                    alert('da Bozza a Pubblicato-invia_notifica_app: '+invia_notifica_app);
                                }

                                if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                                    var nome_autore  = document.getElementById('nome_autore').value;
                                    //alert('nome_autore: '+nome_autore);
                                }
                                
                                //#	----------------------------------------- Nuova Notifica POST NEWS--------------------------------------------------
                                /*
                                async function nuovaNotificaPostNewsBozzaPublish() {
                                        const request = await fetch("https://v1-api.lafull.it/api/notification/send", {
                                                        
                                            method: "POST",
                                            headers: {
                                                "content-type": "application/json",
                                            },
                                            body: JSON.stringify(
                                                {
                                                "title": titolo,
                                                "body" : riassunto,
                                                })
                                        })
                                    
                                    // Richiama la funzione della notifica
                                    const json = await request.json();
                                    
                                    
                                    if (json.success){
                                        alert("POST INSERITO da bozza-publish Notifica inviata: "+ json.success);
                                    }else{
                                        alert("Errore Notifica");	
                                    }
                                    
                                }
                                */

                                
                                async function nuovoPostNewsBozzaPublish() {
                                        //alert ('in funzione NUOVO da Bozza-Publish:'+id);
                                        
                                        const request = await fetch("https://v1-api.lafull.it/api/news/", {
                                                        
                                            method: "POST",
                                            headers: {
                                                "content-type": "application/json",
                                            },
                                            body: JSON.stringify(
                                                {
                                                "mySqlId"  : id,
                                                "title"    : titolo,
                                                "author"   : nome_autore,
                                                "subtitle" : riassunto,
                                                "field"    : contenuto,
                                                "category" : allCatToArray,
                                                "image"    : url,
                                                "video"    : video,
                                                "YTvideo"  : video_youtube,
                                                "notificationField" : invia_notifica_app,
                                                })
                                        })
                                    
                                    // Richiama la funzione della notifica
                                    const json = await request.json();

                                    if (json.success) {
                                        //await nuovaNotificaPostNewsBozzaPublish();
                                        alert ("Post Pubblicato da Bozza");
                                    }else{
                                        alert("Errore Post in APP non pubblicato DA BOZZA draft-publish");
                                    }

                                    // var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                                    // window.location.assign(url_edit);
                                    history.back();
                                }
                                nuovoPostNewsBozzaPublish();
                            </script>
                        <?php
                    //---Fine nuovo post --//
                    // FINE POST NUOVO DA BOZZA 
                    die();		
                }

                //DA PROGRAMMATO A PROGRAMMATO Aggiornamento Post future-future con Notifica FAtta e yuotube
                if ($cc_tipo_post == 'future' && $post->post_status == 'future'){
                        echo '<h4>FUTURE</h4>';
                        update_post_meta($post_ID,'cc_tipo_post','future');

                        $_SESSION['post_status'] = "future";
                        $_SESSION['reload'] = 'si';
                        $_SESSION['pubblicato'] = "PROGRAMMATO E AGGIORNATO";

                        echo 'Post Data programmazione: '.$post->post_date.'<br>';
                        $data_programmazione = $post->post_date;
                        $data_programmazione_unix = strtotime($post->post_date);
                        echo 'Data programmazione: '.$data_programmazione.'<br>';
                        echo '<h4>Post Programmato</h4>';
                        echo 'Unix Data $data_programmazione_unix;'.$data_programmazione_unix;
                        ?>
    
                        <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                        <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                        <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
                        
                        <textarea id="contenuto" type="text">
                        <?php echo $content; ?>
                        </textarea>
            
                        <input id="url" type="text" value="<?php echo $url; ?>"/>
                        <input id="video" type="text" value="<?php echo $video; ?>"/>
                        <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                        <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                        <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                        <input id="data_programmazione" type="text" value="<?php echo $data_programmazione_unix; ?>"/>
                        <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                        <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                        <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                        <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                        <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>

                        <script>
                            
                            var id          = document.getElementById('postid').value;
                            var titolo      = document.getElementById('titolo').value;
                            var riassunto   = document.getElementById('riassunto').value;
                            var contenuto   = document.getElementById('contenuto').value;
                            var url         = document.getElementById('url').value;
                            var video       = document.getElementById('video').value;
                            var video_youtube  = document.getElementById('video_youtube').value;
                            var invia_notifica_app = document.getElementById('invia_notifica_app').value;
                            var nome_autore = document.getElementById('nome_autore').value;
                            var DataProgrammazione = document.getElementById('data_programmazione').value;
                        
                            var cat0 = document.getElementById('cat0').value;
                            var cat1 = document.getElementById('cat1').value;
                            var cat2 = document.getElementById('cat2').value;
                            var cat3 = document.getElementById('cat3').value;
                            var cat4 = document.getElementById('cat4').value;
                            
                
                            let allCatToArray = [];
                            if (cat0){
                                allCatToArray.push(cat0);
                            }
                            if (cat1){
                                allCatToArray.push(cat1);
                            }
                            if (cat2){
                                allCatToArray.push(cat2);
                            }
                            if (cat3){
                                allCatToArray.push(cat3);
                            }
                            if (cat4){
                                allCatToArray.push(cat4);
                            }
                        
                
                            if (typeof(id) != 'undefined' && id != null) {
                                var id  = document.getElementById('postid').value;
                                //alert('Id: '+id);
                            }
                
                            if (typeof(riassunto) != 'undefined' && riassunto != null) {
                                var riassunto  = document.getElementById('riassunto').value;
                                //alert('Riassunto: '+riassunto);
                            }
                
                            if (typeof(contenuto) != 'undefined' && contenuto != null) {
                                var contenuto  = document.getElementById('contenuto').value;
                                //alert('Contenuto: '+contenuto);
                            }
                
                            if (typeof(url) != 'undefined' && url != null) {
                                var url  = document.getElementById('url').value;
                                //alert('Url: '+url);
                            }
                
                            if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                                var nome_autore  = document.getElementById('nome_autore').value;
                                //alert('nome_autore: '+nome_autore);
                            }
                
                            
                            if (typeof(DataProgrammazione) != 'undefined' && DataProgrammazione != null) {
                                var DataProgrammazione  = document.getElementById('data_programmazione').value;
                                //alert('data: '+DataProgrammazione);
                            }

                            if (typeof(video) != 'undefined' && video != null) {
                                    var video  = document.getElementById('video').value;
                                    //alert('Video: '+video);
                            }

                            if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                    var video_youtube  = document.getElementById('video_youtube').value;
                                    alert('Video_youtube: '+video_youtube);
                            }

                            if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                                var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                                //alert('AGGIORNAMENTO-invia_notifica_app: '+invia_notifica_app);
                            }
                            
                            /*
                            async function notificaNews() {
                                    const request = await fetch("https://v1-api.lafull.it/api/notification/send", {
                                                    
                                        method: "POST",
                                        headers: {
                                            "content-type": "application/json",
                                        },
                                        body: JSON.stringify(
                                            {
                                            "title": titolo,
                                            "body" : riassunto,
                                            })
                                    })
                                
                                // Richiama la funzione della notifica
                                const json = await request.json();
                                
                                
                                if (json.status){
                                    alert("Notifica inviata: "+ json.status);
                                }else{
                                    alert("Errore Notifica");	
                                }
                                
                                
                                //window.location = url_edit+'?post='+parseInt(id)+'&action=edit';
                                //history.back();
                            }
                            */
                
                            
                            async function aggiornamentoNewsProgrammata() {
                                    alert ('POST PROGRAMMATO AGGIORNATO id:'+id);
                                    //alert(typeof DataProgrammazione);
                                    const request = await fetch("https://v1-api.lafull.it/api/jobs/"+id+"?unixDate="+DataProgrammazione, {
                                                    
                                        method: "POST",
                                        headers: {
                                            "content-type": "application/json",
                                        },
                                        body: JSON.stringify(
                                            {
                                            "data" : 
                                                {
                                                "mySqlId"  : id,
                                                "title"    : titolo,
                                                "author"   : nome_autore,
                                                "subtitle" : riassunto,
                                                "field"    : contenuto,
                                                "category" : allCatToArray,
                                                "image"    : url,
                                                "video"    : video,
                                                "YTvideo"  : video_youtube,
                                                "notificationField" : invia_notifica_app,
                                                }	
                                            })
                                    })
                                
                                // Richiama la funzione della notifica
                                const json = await request.json();
                                
                                /*
                                if (json.notifyMessage) {
                                    await notificaNews();
                                }else{
                                    alert("Errore Post in APP non pubblicato");
                                }
                                */
                                

                                // var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                                // window.location.assign(url_edit);
                                history.back();
                                
                            }
                        
                            aggiornamentoNewsProgrammata();
                                            
                        </script>
                        <?php
                    die();	  
                }

                //DA PUBBLICATO A PUBBLICATO Aggiornamento post già pubblicato publish-publish con Notifica FAtta
                if ($cc_tipo_post == 'publish' && $post->post_status == 'publish'){
                    echo '<h4>Updated->Publish</h4>';
                    update_post_meta($post_ID,'cc_tipo_post','publish');

                    $_SESSION['post_status'] = "publish";
                    $_SESSION['reload'] = 'si';
                    $_SESSION['pubblicato'] = "PUBBLICATO E AGGIORNATO";

                    ?>

                    <input id="postid"    type=hidden value="<?php echo $post_ID; ?>"/>
                    <input id="titolo"    type=hidden value="<?php echo $titolo; ?>"/>
                    <input id="riassunto" type=hidden value="<?php echo $riassunto; ?>"/>
            
                    <textarea id="contenuto" type="text">
                    <?php echo $content; ?>
                    </textarea>
            
                    <input id="url" type="text" value="<?php echo $url; ?>"/>
                    <input id="video" type="text" value="<?php echo $video; ?>"/>
                    <input id="video_youtube" type="text" value="<?php echo $video_youtube; ?>"/>
                    <input id="invia_notifica_app" type="text" value="<?php echo $invia_notifica_app; ?>"/>
                    <input id="nome_autore" type="text" value="<?php echo $nome_autore; ?>"/>
                    
                    <input id="cat0" type="text" value="<?php echo $array_lista_categorie[0]; ?>"/>
                    <input id="cat1" type="text" value="<?php echo $array_lista_categorie[1]; ?>"/>
                    <input id="cat2" type="text" value="<?php echo $array_lista_categorie[2]; ?>"/>
                    <input id="cat3" type="text" value="<?php echo $array_lista_categorie[3]; ?>"/>
                    <input id="cat4" type="text" value="<?php echo $array_lista_categorie[4]; ?>"/>
            
                    <script>
                        
                        var id          = document.getElementById('postid').value;
                        var titolo      = document.getElementById('titolo').value;
                        var riassunto   = document.getElementById('riassunto').value;
                        var contenuto   = document.getElementById('contenuto').value;
                        var url         = document.getElementById('url').value;
                        var video       = document.getElementById('video').value;
                        var video_youtube  = document.getElementById('video_youtube').value;
                        var invia_notifica_app = document.getElementById('invia_notifica_app').value;
                        var nome_autore = document.getElementById('nome_autore').value;
                        //var DataProgrammazione = document.getElementById('data_programmazione').value;
                    
                        var cat0 = document.getElementById('cat0').value;
                        var cat1 = document.getElementById('cat1').value;
                        var cat2 = document.getElementById('cat2').value;
                        var cat3 = document.getElementById('cat3').value;
                        var cat4 = document.getElementById('cat4').value;
                        
                        //alert("qui ID: "+id);
                        let allCatToArray = [];
                        if (cat0){
                            allCatToArray.push(cat0);
                        }
                        if (cat1){
                            allCatToArray.push(cat1);
                        }
                        if (cat2){
                            allCatToArray.push(cat2);
                        }
                        if (cat3){
                            allCatToArray.push(cat3);
                        }
                        if (cat4){
                            allCatToArray.push(cat4);
                        }
                    
                        if (typeof(id) != 'undefined' && id != null) {
                            var id  = document.getElementById('postid').value;
                            //alert('Id: '+id);
                        }
            
                        if (typeof(riassunto) != 'undefined' && riassunto != null) {
                            var riassunto  = document.getElementById('riassunto').value;
                            //alert('Riassunto: '+riassunto);
                        }
            
                        if (typeof(contenuto) != 'undefined' && contenuto != null) {
                            var contenuto  = document.getElementById('contenuto').value;
                            //alert('Contenuto: '+contenuto);
                        }
            
                        if (typeof(url) != 'undefined' && url != null) {
                            var url  = document.getElementById('url').value;
                            //alert('Url: '+url);
                        }

                        if (typeof(video) != 'undefined' && video != null) {
                            var video  = document.getElementById('video').value;
                            //alert('Video: '+video);
                        }

                        if (typeof(video_youtube) != 'undefined' && video_youtube != null) {
                                    var video_youtube  = document.getElementById('video_youtube').value;
                                    alert('Video_youtube: '+video_youtube);
                        }

                        if (typeof(invia_notifica_app) != 'undefined' && invia_notifica_app != null) {
                            var invia_notifica_app  = document.getElementById('invia_notifica_app').value;
                            alert('AGGIORNAMENTO-invia_notifica_app: '+invia_notifica_app);
                        }
            
                        if (typeof(nome_autore) != 'undefined' && nome_autore != null) {
                            var nome_autore  = document.getElementById('nome_autore').value;
                            alert('nome_autore: '+nome_autore);
                        }
                        
                        //#	----------------------------------------- INVIA NOTIFICA IN AGGIORNA-------------------------------------------------
                        /*
                        async function aggiornaNotificaPostNews() {
                                const request = await fetch("https://v1-api.lafull.it/api/notification/send", {
                                                
                                    method: "POST",
                                    headers: {
                                        "content-type": "application/json",
                                    },
                                    body: JSON.stringify(
                                        {
                                        "title": titolo,
                                        "body" : riassunto,
                                        })
                                })
                            
                            // Richiama la funzione della notifica
                            const json = await request.json();
                            
                            if (json.success){
                                alert("Nuova notifica AGGIORNO POST publish-publish: Notifica Inviata: "+ json.success);
                            }else{
                                alert("Errore Notifica - publish-publish:");	
                            }
                        
                        }
                        */
            
                        //#	----------------------------------------- Aggiorna POST --------------------------------------------------
                        async function aggiornaPostNews() {
                                //alert ('AGGIORNO POST - PUT publish-publish: '+id);
                                const request = await fetch("https://v1-api.lafull.it/api/news/"+id, {
                                                
                                    method: "PUT",
                                    headers: {
                                        "content-type": "application/json",
                                    },
                                    body: JSON.stringify(
                                        {
                                        "title"    : titolo,
                                        "author"   : nome_autore,
                                        "subtitle" : riassunto,
                                        "field"    : contenuto,
                                        "category" : allCatToArray,
                                        "image"    : url,
                                        "video"    : video,
                                        "YTvideo"  : video_youtube,
                                        "notificationField" : invia_notifica_app,
                                        })
                                })
                            
                            // Richiama la funzione della notifica
                            const json = await request.json();
            
                            if (json.success) {
                                //await aggiornaNotificaPostNews();
                                alert ("Post Aggiornato!");
                            }else{
                                alert("Errore Post in APP non pubblicato publish-publish:");
                            }
            
                                                
                            //-----------###########################-------------------------#
                            //localStorage.setItem("reload","si");
                            history.back();

                            /*
                            var url_edit = 'https://qui.calabriaunited.com/wp-admin/edit.php';
                            window.location.assign(url_edit);
                            */

                                //alert(id+'-'+titolo);
                                
                                //history.back();
                                //alert(url_edit);
                                //window.location.replace(url_edit);
                                
                                //document.referrer ? window.location = document.referrer : history.back()
                                //window.location = url_edit+'?post='+parseInt(id)+'&action=edit';

                            //document.referrer ? window.location = document.referrer : history.back()
                            //history.back();
                            //window.location = url_edit+'?post='+parseInt(id)+'&action=edit';
                            
                        }
                        aggiornaPostNews();
                    </script>
                    <?php
                    die();
                }
                die();	
            }

        }else{

            update_post_meta($post_ID,'cc_id_post',$post_ID);
            update_post_meta($post_ID,'cc_tipo_post',$post->post_status);
            $_SESSION['post_status'] = $post->post_status;
            $_SESSION['reload'] = 'no';

        }
    }
    
}

add_action('wp_insert_post', 'elimina_da_app', 10, 3);    
function elimina_da_app($post_ID, $post = null, $update = true) {
    if ($post->post_type == 'post'){

        //Valorizzo se è stato premuto pubblica o aggiorna
        if ($update){
            $_SESSION['aggiorna_in_app'] = 'aggiorna';
            $_SESSION['aggiorna_in_app_idpost'] = $post_ID;
        }
            
    }
}

function wpdocs_trash_multiple_posts( $post_id = '' ) {

    $cc_tipo_post = get_post_meta($post_id,'cc_tipo_post',true);
    if ($cc_tipo_post == 'draft' ){
        wp_redirect('https://quicosenzastage.lafull.it/news/wp-admin/edit.php');
        exit;
    }

    /*
    print_r($_GET['post']);
    echo '<hr>';
    print_r($_SESSION['elimina_tutti_in_app']);
    die();
    */

    // Importante per eliminare la sessione prima di cancellare
    unset($_SESSION['elimina_tutti_in_app']);

    // Verify if is trashing multiple posts
    if ( isset( $_GET['post'] ) && is_array( $_GET['post'] ) ) {
        foreach ( $_GET['post'] as $post_id ) {
            $_SESSION['elimina_tutti_in_app'][] = $post_id;
        }
    } else {
            $_SESSION['elimina_tutti_in_app'][] = $post_id;
    }
}
add_action( 'wp_trash_post', 'wpdocs_trash_multiple_posts' );

function add_appquicosenza_columns($columns) {
    //unset($columns['comments']);
    //unset($columns['author']);
    return array_merge($columns, 
            array('cc_tipo_post' => __('Stato in APP'),
                    'invia_notifica_app' =>__( 'Notifica in APP')));
}
add_filter('manage_post_posts_columns' , 'add_appquicosenza_columns');

function custom_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'invia_notifica_app':

            /*
            $terms = get_the_term_list( $post_id, 'book_author', '', ',', '' );
            if ( is_string( $terms ) ) {
                echo $terms;
            } else {
                _e( 'Unable to get author(s)', 'your_text_domain' );
            }
            */

            if (get_post_meta( $post_id, 'invia_notifica_app', true )== 'si'){
                echo '<span style="color:green; font-size:14px"><b>Si</b></span>';
            }else{
                echo '<span style="color:red; font-size:14px">No</span>';
                
            }
            break;

        case 'cc_tipo_post':
            $stato_del_post_in_app = get_post_meta( $post_id, 'cc_tipo_post', true );
            $post = get_post($post_id);

            switch ($stato_del_post_in_app) {
                case "publish":
                    echo '<span style="color:green"><b>Pubblicato in APP </b><br><span style="color:grey"> (publish)</span></span>';
                    break;
                case "future":
                    if ($stato_del_post_in_app=='future' && $post->post_status == 'publish'){
                        echo '<span style="color:green"><b>Pubblicato in APP da Programmato </b><br><span style="color:grey"> (future to publish)</span></span>';
                    }else{
                        //orange
                        echo '<span style="color:blue"><b>Programmato in APP</b><br><span style="color:grey"> (future)</span></span>';
                    }
                    break;
                case 'auto-draft':
                    echo '<span style="color:red"><b>NON in APP</b><br><span style="color:grey"> (auto-draft)</span></span>';
                    update_post_meta( $post_id, 'invia_notifica_app', 'no');
                    break;
                case 'draft':
                    echo '<span style="color:red"><b>NON in APP </b><br><span style="color:grey"> (draft)</span></span>';
                    break;	
                case 'trash':
                    echo '<span style="color:grey"><b>NON in APP</b><br><span style="color:grey"> (trash)</span></span>';
                    break;		
                default:
                    echo '<span style="color:red"><b>NON in APP</b><br><span style="color:grey"> (vuoto)</span></span>';
                    update_post_meta( $post_id, 'cc_tipo_post', 'auto-draft');
                    update_post_meta( $post_id, 'invia_notifica_app', 'no');
                    break;
            }

            if (isset($_SESSION['aggiorna_in_app'])){
                if ($_SESSION['aggiorna_in_app'] = 'aggiorna'){
                    if ($_SESSION['aggiorna_in_app_idpost'] == $post_id){
                        echo '<br><span style="color:red"><b>Aggiornare questo post in APP</b></span>';
                    }
                }
            }
            
            break;
    }
}
add_action( 'manage_posts_custom_column' , 'custom_columns', 10, 2 );
?>