<?php

    class ViewAjax extends View {
        /*renderiza en fomato json los datos del get esto solo sirve para ajax*/
        function render() {
            $data=$this->getModel()->getData();
            return $data['json'];
        }
    }
    
?>