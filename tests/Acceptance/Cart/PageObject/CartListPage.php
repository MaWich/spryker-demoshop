<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Acceptance\Cart\PageObject;

class CartListPage
{

    const START_CHECKOUT_XPATH = '/html/body/div[2]/main/div/div[2]/div/p/a';
    const CART_HEADER = 'Cart';

    const FIRST_CART_ITEM_QUANTITY_INPUT_XPATH = '/html/body/div[2]/main/div[1]/div/div/div/div[4]/ul/li[1]/form/label/span/input';
    const FIRST_CART_ITEM_CHANGE_QUANTITY_BUTTON_XPATH = '/html/body/div[2]/main/div[1]/div/div/div/div[4]/ul/li[1]/form/label/span/span/button';

}
