<?php

use Carbon_Fields\Container;
use Carbon_Fields\Complex_Container;
use Carbon_Fields\Field;



/*-----------------------------------------------------------------------------------*/
/* Products Included
/*-----------------------------------------------------------------------------------*/
Container::make('post_meta', 'Product Included')
	->where('post_type', '=', 'solutions')
	->add_fields(
		array(
            Field::make('association', 'products_included', 'Select Product Included')
            ->set_types(array(
                array(
                    'type'      => 'post',
                    'post_type' => 'product',
                )
            )),
		)
	);
