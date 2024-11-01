<?php
/* Generates different form elements
 * By Stormtribe Development
 * License Free, use on your own risk

Examples:
$Cforms = new Cstdev_forms('0.4.2'); //Where 0.4.2 is the lowest version requiered.

$elements = array(
    "app_title" => array(
        'type' => 'title',
        'title' => __('App name', 'stdev-sc'),
        'h_size' => 3
        ),
    "app_version" => array(
        'type' => 'text',
        'title' => __('App version', 'stdev-sc'),
        'desc' => __('Enter current version of the application', 'stdev-sc'),
        'meta' => 'maxlength="16"'
        )
);

*/


if (!class_exists('C_stdev_forms', false))
{
    class C_stdev_forms
    {
        const version = '0.4.2'; //Version of this class
        // x.y.z (x=major changes, y=some changes, z=fixes)
        
        var $xhtml = false;
        
        function __construct($minVersion = null, $use_xhtml = false)
        {
            $this->xhtml = $use_xhtml;
            
            if ($minVersion !== null)
                if (version_compare(constant("self::version") , $minVersion) < 0)
                    trigger_error(sprintf("C_stdev_forms class required(%s) is lower than this class version(%s)", $minVersion, constant("self::version")), E_USER_WARNING);
        }
        
        public function is_const($type)
        {
            if (in_array($type, array('submit', 'title', 'title_with_info', 'custom_second_td')))
                return true;
            else
                return false;
        }
        
        public function get_generated_form($elements)
        {
            $do_group = false;
            $ret = '';
            $grp_style_first = ''; $grp_style_second = '';
            foreach ($elements as $key => $element)
            {
                $ret .= '<tr valign="top">';
                $element['name'] = $key;
                if (isset($element['meta'])==false)
                    $element['meta'] = '';
                if (isset($element['desc'])==false)
                    $element['desc'] = '';
                switch($element['type'])
                {
                    case 'group_start':
                        $color = $element['color'];
                        $ret .= '<td colspan="2" style="border-bottom: solid 2px '.$color.';">';
                        $grp_style_first = ' style="border-left: solid 2px '.$color.'"';
                        $grp_style_second = ' style="border-right: solid 2px '.$color.'"';
                        break;
                    case 'group_end':
                        $ret .= '<td colspan="2" style="border-top: solid 2px '.$color.';">';
                        $grp_style_first = '';
                        $grp_style_second = '';
                        break;
                    case 'echo':
                        $ret .= '<td'.$grp_style_first.'><label for="'.$element['name'].'">'.$element['title'].'</label></td><td'.$grp_style_second.'>';
                        if (!isset($element['value']) || isset($element['value']) === null)
                            $element['value'] = $element['default'];
                        $ret .= $element['value'];
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'title':
                        $ret .= '<td colspan="2"'.$grp_style_first.$grp_style_second.'><h'.$element['h_size'].' class="title">'.$element['title'].'</h'.$element['h_size'].'>';
                        break;
                    case 'title_with_info':
                        $ret .= '<td'.$grp_style_first.$grp_style_second.'><h'.$element['h_size'].' class="title">'.$element['title'].'</h'.$element['h_size'].'></td>';
                        $ret .= '<td><p>'.$element['label'].'</p>';
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'hidden':
                        $ret .= $this->do_hiddenfield($element);
                        break;
                    case 'checkbox':
                        $ret .= '<td'.$grp_style_first.'>'.$element['title'].'</td><td'.$grp_style_second.'>';
                        $ret .= '<label for="'.$element['name'].'">'.$this->do_checkboxfield($element).'</label>';
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'select':
                    case 'multiselect':
                        $ret .= '<td'.$grp_style_first.'><label for="'.$element['name'].'">'.$element['title'].'</label></td><td'.$grp_style_second.'>';
                        $ret .= $this->do_selectfield($element);
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'submit':
                        $ret .= '<td'.$grp_style_first.'>'.$element['title'].'</td><td'.$grp_style_second.'>';
                        $ret .= $this->do_submitfield($element);
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'button':
                        $ret .= '<td'.$grp_style_first.'>'.$element['title'].'</td><td'.$grp_style_second.'>';
                        $ret .= $this->do_buttonfield($element);
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'custom_second_td':
                        $ret .= '<td'.$grp_style_first.'>'.$element['title'].'</td><td'.$grp_style_second.'>';
                        $ret .= $element['data'];
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'textarea':
                        $ret .= '<td'.$grp_style_first.'><label for="'.$element['name'].'">'.$element['title'].'</label></td><td'.$grp_style_second.'>';
                        $ret .= $this->do_textareafield($element);
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    case 'select_optgroup':
                    case 'multiselect_optgroup':
                        $ret .= '<td'.$grp_style_first.'><label for="'.$element['name'].'">'.$element['title'].'</label></td><td'.$grp_style_second.'>';
                        $ret .= $this->do_select_optgroupfield($element);
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                    
                    case 'text':
                    default:
                        $ret .= '<td'.$grp_style_first.'><label for="'.$element['name'].'">'.$element['title'].'</label></td><td'.$grp_style_second.'>';
                        $ret .= $this->do_textfield($element);
                        $ret .= '<p class="description">'.$element['desc'].'</p>';
                        break;
                }
                
                $ret .= '</td></tr>';
            }
            
            return $ret;
        }
        
        private function do_checkboxfield($element)
        {
            if (!isset($element['value']))
                if (!isset($element['default']))
                    $element['value'] = '1';
                else 
                    $element['value'] = $element['default'];

            return '<input type="checkbox" name="'.$element['name'].'" id="'.$element['name'].'" value="1" '.$element['meta'].' '.$this->check_checkbox($element).' />&nbsp;'.$element['label'];
        }
        private function do_textfield($element)
        {
            if (!isset($element['value']) || isset($element['value']) === null)
                $element['value'] = $element['default'];
            return '<input type="text" name="'.$element['name'].'" id="'.$element['name'].'" value="'.$element['value'].'" '.$element['meta'].' />';
        }
        private function do_hiddenfield($element)
        {
            if (!isset($element['value']) || isset($element['value']) === null)
                $element['value'] = $element['default'];
            return '<input type="hidden" name="'.$element['name'].'" value="'.$element['value'].'" />';
        }    
        private function do_textareafield($element)
        {
            if (!isset($element['value']) || isset($element['value']) === null)
                $element['value'] = $element['default'];
            return '<textarea name="'.$element['name'].'" id="'.$element['name'].'" '.$element['meta'].'>'.$element['value'].'</textarea>';
        }
        private function do_selectfield($element)
        {
            if (!isset($element['value']) || $element['value'] === null)
                if (isset($element['default']))
                    $element['value'] = $element['default'];
                else
                    $element['value'] = null;
            
            if ($element['type'] === 'select')
            {
                $ret = '<select name="'.$element['name'].'" '.$element['meta'].'>';
                foreach ($element['options'] as $key => $name)
                {
                    if ($element['value'] == $key || $element['value'] === 'STDEV_SELECT_ALL')
                        if (!$this->xhtml)
                            $ret.= '<option value="'.$key.'" selected>'.$name.'</option>';
                        else
                            $ret.= '<option value="'.$key.'" selected="selected">'.$name.'</option>';
                    else
                        $ret.= '<option value="'.$key.'">'.$name.'</option>';
                }
            }
            else //multiselect
            {
                $ret = '<select name="'.$element['name'].'[]" multiple '.$element['meta'].'>';
                foreach ($element['options'] as $key => $name)
                {
                    if (($element['value'] != null && $element['value'][$key] == true) || $element['value'] === 'STDEV_SELECT_ALL')
                        if (!$this->xhtml)
                            $ret.= '<option value="'.$key.'" selected>'.$name.'</option>';
                        else
                            $ret.= '<option value="'.$key.'" selected="selected">'.$name.'</option>';
                    else
                        $ret.= '<option value="'.$key.'">'.$name.'</option>';
                }
            }
            $ret .= '</select>';
            return $ret;
        }
        private function do_submitfield($element)
        {
            return '<input type="submit" name="'.$element['name'].'" value="'.$element['value'].'" '.$element['meta'].' />';
        }
        private function do_buttonfield($element)
        {
            return '<input type="button" name="'.$element['name'].'" value="'.$element['value'].'" '.$element['meta'].' />';
        }
        
        private function do_select_optgroupfield($element)
        {
            if (!isset($element['value']))
                if (isset($element['default']))
                    $element['value'] = $element['default'];
                else
                    $element['value'] = null;
            
            if ($element['type'] === 'select_optgroup')
            {
                $ret = '<select name="'.$element['name'].'" '.$element['meta'].'>';
                foreach ($element['options'] as $group)
                {
                    $ret .= '<optgroup label="'.$group['label'].'">';
                    foreach ($group['options'] as $key => $name)
                    {
                        if ($element['value'] == $key || $element['value'] === 'STDEV_SELECT_ALL')
                            if (!$this->xhtml)
                                $ret.= '<option value="'.$key.'" selected>'.$name.'</option>';
                            else
                                $ret.= '<option value="'.$key.'" selected="selected">'.$name.'</option>';
                        else
                            $ret.= '<option value="'.$key.'">'.$name.'</option>';
                    }
                    $ret .= '</optgroup>';
                }
            }
            else //multiselect
            {
                $ret = '<select name="'.$element['name'].'[]" multiple '.$element['meta'].'>';
                foreach ($element['options'] as $group)
                {
                    $ret .= '<optgroup label="'.$group['label'].'">';
                    foreach ($group['options'] as $key => $name)
                    {
                        if (($element['value'] != null && $element['value'][$key] == true) || $element['value'] === 'STDEV_SELECT_ALL')
                            if (!$this->xhtml)
                                $ret.= '<option value="'.$key.'" selected>'.$name.'</option>';
                            else
                                $ret.= '<option value="'.$key.'" selected="selected">'.$name.'</option>';
                        else
                            $ret.= '<option value="'.$key.'">'.$name.'</option>';
                    }
                    $ret .= '</optgroup>';
                }
            }
            $ret .= '</select>';
            return $ret;
        }        
        
        private function check_checkbox($element)
        {
            if ($element['value'] === 1 || $element['value'] === true || $element['value'] === 'yes' || $element['value'] === '1' || $element['value'] === 'checked')
                if (!$this->xhtml)
                    return 'checked';
                else
                    return 'checked="checked"';
        }
        
        public function generate_select_box_from_array($select_name, $array, $meta = '')
        {
            $ret = '<select name="'.$select_name.'" '.$meta.'>';
            foreach ($array as $key => $name)
                $ret.= '<option value="'.$key.'">'.$name.'</option>';
            $ret .= '</select>';
            return $ret;
            
        }
    }
    
}

?>