<?php
/**
 * Wrapper function for the PL_Debug::dump() function.
 * 
 * @access public
 * @return void
 */
function pl_dump() {
    $args = func_get_args();
    PL_Debug::dump( $args );
}

PL_Debug::init();
/**
 * A class that includes theme debugging functions
 *
 * @static
 */
class PL_Debug {

    static $debug_messages = array();
    static $message_text = '';

    static $show_debug = false;

    

    static function init() {
 
        add_action('wp_footer', array(__CLASS__, 'show_window' ) );
        add_action('admin_footer', array(__CLASS__, 'show_window' ) );

    }



    static function show_window () {
        

        // optionally show debug messages.  
        if (self::$show_debug) {
            self::assemble_messages();
            ?>
            <div style="position:fixed; bottom: 0px; left: 0px; width:100%; height: 35%; background-color: #F8F8F8 ; overflow: auto; border-top: 2px solid black; font-size: 11px; color: black;">
                <h4>Blueprint Debug Messages</h4>
                <?php echo self::$message_text; ?>    
            </div>
            <?php
        }   

    }
    // adds routing messages for easy debugging.
    // TODO: Move this to a global class so devs
    // turn it on easily and see what's going on. 
    static function add_msg ($new_message)
    {
        self::$debug_messages[] = $new_message;
        
    }


    static function assemble_messages ($messages_array = false) {

        self::$message_text = "<ul>";
        
        foreach ( (array) self::$debug_messages as $message) {
            self::$message_text .= self::style_message($message);
        }

        self::$message_text .= "</ul>";
    }

    static function style_message ($message, $indent = false) {
        
        $styled_message = "<li>";
        
        if ($indent) {
            $styled_message .= "<ul>";
        }

        if ( is_array($message) || is_object($message) ) {
            foreach ($message as $item) {
                $styled_message .= self::style_message($item, true);
            }
        } else {
            $styled_message .= $message;
        }
        
        if ($indent) {
            $styled_message .= "</ul>";
        }
        
        $styled_message .= "</li>";

        return $styled_message;
    }

    /**
     * Dumps a variable for debugging purposes
     * 
     * @param mixed $data The variable that needs to be dumped.
     * @static
     */
    static function dump() {
        $args = func_get_args();
        /**
         *  If the given variable is an array use print_r
         */
        foreach ( $args as $data ) {
            if( is_array( $data ) ) {
                print "<pre>-----------------------\n";
                print_r( $data );
                print "-----------------------</pre>\n";
            } elseif ( is_object( $data ) || is_bool( $data ) ) {
                print "<pre>==========================\n";
                var_dump( $data );
                print "===========================</pre>\n";
            } else {
                print "<pre>=========&gt; ";
                echo $data;
                print " &lt;=========</pre>";
                echo "\n";
            }
        }
    }



//end class
}
