<?php
/**
 * @package castawaystravel
 * Add CSS an JS Archives
 */

 namespace Inc\Base\MyDeals;

 use \Inc\Base\General\BaseController;

 class MyDealCustomFields extends BaseController
 {
    public function register()
    {
        add_action( 'add_meta_boxes', array($this,'add_custom_fields' ));
        add_action( 'save_post' , array($this , 'save_deals'), 1, 2 );
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_media']); // ← para wp.media
		add_shortcode('deal_logo', [$this, 'render_deal_logo']);
    }
    /*
    *   ==========================================
    *       DateTime Class
    *   ==========================================
    */

    function add_custom_fields(){
		
        $post_type = 'deal';
        $context = 'advanced';
        $priority = 'high';

        add_meta_box('start-date', 'Start date', array($this,'start_date'), $post_type,  $context, $priority);
        add_meta_box('end-date', 'End date', array($this,'end_date'), $post_type,  $context, $priority);
        add_meta_box('book-now-url', 'Book url', array($this,'book_url'), $post_type,  $context, $priority);
		add_meta_box('deal-logo', 'Deal logo', array($this,'deal_logo'), $post_type,  $context, $priority);
    }


    /*
    *   ==========================================
    *       Add Start date field
    *   ==========================================
    */
    function start_date($post) {
    $post_id = get_the_ID();
    $start_date = get_post_meta($post_id, 'wpcf-deal-start-date', true);
    $format_start_date = is_numeric($start_date) ? date("Y-m-d", (int)$start_date) : '';

    if ($start_date) {
        echo '<div class="fields_container">';
        echo '<div class="fields_item">Update?</div>';
        echo '<div class="fields_item">';
        echo '<input type="date" name="wpcf-deal-start-date" id="wpcf-deal-start-date" value="'.esc_attr($format_start_date).'" />';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<input type="date" name="wpcf-deal-start-date" id="wpcf-deal-start-date" />';
    }
    wp_nonce_field('_namespace_form_metabox_dates', '_namespace_form_metabox_dates_fields');
}

function end_date($post) {
    $post_id = get_the_ID();
    $end_date = get_post_meta($post_id, 'wpcf-deal-end-date', true);
    $format_end_date = is_numeric($end_date) ? date("Y-m-d", (int)$end_date) : '';

    if ($end_date) {
        echo '<div class="fields_container">';
        echo '<div class="fields_item">Update?</div>';
        echo '<div class="fields_item">';
        echo '<input type="date" name="wpcf-deal-end-date" id="wpcf-deal-end-date" value="'.esc_attr($format_end_date).'"/>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<input type="date" name="wpcf-deal-end-date" id="wpcf-deal-end-date" />';
    }
    wp_nonce_field('_namespace_form_metabox_dates', '_namespace_form_metabox_dates_fields');
}

function book_url($post) {
    $post_id = get_the_ID();
    $book_url = get_post_meta($post_id, 'wpcf-deal-url', true);

    if ($book_url) {
        echo '<div class="fields_container">';
        echo '<div class="fields_item">Update?</div>';
        echo '<div class="fields_item">';
        echo '<input style="min-width: 100%;" type="text" name="wpcf-deal-url" id="wpcf-deal-url" value="'.esc_attr($book_url).'"/>';
        echo '</div>';
        echo '</div>';
    } else {
        echo '<input type="text" style="min-width: 100%;" name="wpcf-deal-url" id="wpcf-deal-url" />';
    }
    wp_nonce_field('_namespace_form_metabox_dates', '_namespace_form_metabox_dates_fields');
}

public function deal_logo($post)
{
    $attachment_id = (int) get_post_meta($post->ID, '_deal_logo_id', true);
    $image_url     = get_post_meta($post->ID, '_deal_logo_url', true);

    // Si hay ID, calcula una imagen de vista previa "medium"
    $preview_src = '';
    if ($attachment_id) {
        $src = wp_get_attachment_image_src($attachment_id, 'medium');
        if ($src && !empty($src[0])) $preview_src = $src[0];
    } elseif (!empty($image_url)) {
        $preview_src = esc_url($image_url);
    }

    // Nonce específico para este metabox
    wp_nonce_field('save_deal_logo', 'deal_logo_nonce');

    echo '<div class="fields_container" style="display:grid;grid-template-columns:1fr;gap:10px;">';

    echo '<div id="deal-logo-preview" style="min-height:10px;">';
    if ($preview_src) {
        echo '<img src="'.esc_url($preview_src).'" style="max-width:240px;height:auto;border:1px solid #ddd;border-radius:6px;">';
    }
    echo '</div>';

    // Inputs ocultos para guardar ID y URL
    echo '<input type="hidden" name="_deal_logo_id" id="_deal_logo_id" value="'.esc_attr($attachment_id).'">';
    echo '<input type="hidden" name="_deal_logo_url" id="_deal_logo_url" value="'.esc_attr($image_url).'">';

    // Botones
    echo '<div>';
    echo '<button type="button" class="button button-primary" id="deal-logo-select">'.($preview_src ? 'Reemplazar imagen' : 'Seleccionar imagen').'</button> ';
    echo '<button type="button" class="button button-secondary" id="deal-logo-remove" '.($preview_src ? '' : 'style="display:none"').'>Quitar imagen</button>';
    echo '</div>';

    echo '</div>';
}
	 
	
public function render_deal_logo($post = null) {
    $post_id = is_numeric($post) ? (int) $post : ( ($post && isset($post->ID)) ? (int) $post->ID : (int) get_the_ID() );
    if (!$post_id) return '';

    $attachment_id = (int) get_post_meta($post_id, '_deal_logo_id', true);
    $image_url     = get_post_meta($post_id, '_deal_logo_url', true);

    // 1) Prioriza ID del adjunto
    if ($attachment_id) {
        return wp_get_attachment_image($attachment_id, 'medium', false, [
            'class'    => 'deal-logo',
            'alt'      => get_the_title($post_id) ?: get_bloginfo('name'),
            'loading'  => 'lazy',
            'decoding' => 'async',
        ]);
    }

    // 2) Fallback URL
    if (!empty($image_url)) {
        return sprintf(
            '<img src="%s" alt="%s" class="%s" loading="lazy" decoding="async">',
            esc_url($image_url),
            esc_attr(get_the_title($post_id) ?: get_bloginfo('name')),
            'deal-logo'
        );
    }

    // 3) Fallback logo del sitio
    $custom_logo_id = (int) get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        return wp_get_attachment_image($custom_logo_id, 'full', false, [
            'class'    => 'deal-logo',
            'alt'      => get_bloginfo('name'),
            'loading'  => 'lazy',
            'decoding' => 'async',
        ]);
    }

    return '';
}




    /*
    *   ==========================================
    *       Save Custom Fields
    *   ==========================================
    */
    function save_deals($post_id, $post){
        // Verify that our security field exists. If not, bail.
        if (!isset($_POST['_namespace_form_metabox_dates_fields'])) return;

        // Verify data came from edit/dashboard screen
        if (!wp_verify_nonce($_POST['_namespace_form_metabox_dates_fields'], '_namespace_form_metabox_dates')) {
            return $post->ID;
        }

        // Verify user has permission to edit post
        if (!current_user_can('edit_post', $post->ID)) {
            return $post->ID;
        }

        if (!isset($_POST['wpcf-deal-start-date'])) {
            return $post->ID;
        }
        if (!isset($_POST['wpcf-deal-end-date'])) {
            return $post->ID;
        }
        if (!isset($_POST['wpcf-deal-url'])) {
            return $post->ID;
        }
        
    
        // Save and update Custom Fields
        // Save and update start-date
        $sanitized_start_date       = wp_filter_post_kses($_POST['wpcf-deal-start-date']);
        if(!empty($_POST['wpcf-deal-start-date'])){
            update_post_meta($post->ID, 'wpcf-deal-start-date', strtotime($sanitized_start_date));
        }
        
        
        // Save and update end-date
        $sanitized_end_date       = wp_filter_post_kses($_POST['wpcf-deal-end-date']);
        if(!empty($_POST['wpcf-deal-end-date'])){
            update_post_meta($post->ID, 'wpcf-deal-end-date', strtotime($sanitized_end_date));
        }
		
		
        // Save and update book-now-url
        $sanitized_book_url       = wp_filter_post_kses($_POST['wpcf-deal-url']);
        if(!empty($_POST['wpcf-deal-url'])){
            update_post_meta($post->ID, 'wpcf-deal-url', $sanitized_book_url);
        }
		
		// --- Guardar Deal Logo ---
		if (isset($_POST['deal_logo_nonce']) && wp_verify_nonce($_POST['deal_logo_nonce'], 'save_deal_logo')) {

			// ID del adjunto (preferido)
			$logo_id = isset($_POST['_deal_logo_id']) ? (int) $_POST['_deal_logo_id'] : 0;

			// URL (opcional, por si decides usarlo en el front)
			$logo_url = isset($_POST['_deal_logo_url']) ? esc_url_raw($_POST['_deal_logo_url']) : '';

			if ($logo_id > 0) {
				update_post_meta($post->ID, '_deal_logo_id', $logo_id);
			} else {
				delete_post_meta($post->ID, '_deal_logo_id');
			}

			if (!empty($logo_url)) {
				update_post_meta($post->ID, '_deal_logo_url', $logo_url);
			} else {
				delete_post_meta($post->ID, '_deal_logo_url');
			}
		}

    }
	 
	 public function enqueue_admin_media($hook)
		{
			$screen = get_current_screen();
		 if (!$screen || $screen->post_type !== 'deal') return;

			// Carga la librería de medios
			wp_enqueue_media();

			// Script mínimo para manejar el frame de medios
			$handle = 'deal-logo-media';
			wp_register_script($handle, false, ['jquery'], '1.0.0', true);
			wp_enqueue_script($handle);

			$inline_js = <<<JS
		jQuery(function($){
		  var frame;
		  function openMediaFrame(onSelect){
			if(frame){ frame.open(); return; }
			frame = wp.media({
			  title: 'Selecciona o sube un logo',
			  button: { text: 'Usar esta imagen' },
			  multiple: false
			});
			frame.on('select', function(){
			  var attachment = frame.state().get('selection').first().toJSON();
			  onSelect(attachment);
			});
			frame.open();
		  }

		  $(document).on('click', '#deal-logo-select', function(e){
			e.preventDefault();
			openMediaFrame(function(attachment){
			  $('#_deal_logo_id').val(attachment.id);
			  $('#_deal_logo_url').val(attachment.url || '');
			  $('#deal-logo-preview').html('<img src="'+(attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url)+'" style="max-width:240px;height:auto;border:1px solid #ddd;border-radius:6px;">');
			  $('#deal-logo-remove').show();
			});
		  });

		  $(document).on('click', '#deal-logo-remove', function(e){
			e.preventDefault();
			$('#_deal_logo_id').val('');
			$('#_deal_logo_url').val('');
			$('#deal-logo-preview').empty();
			$(this).hide();
		  });
		});
		JS;
			wp_add_inline_script($handle, $inline_js);
		}

 }