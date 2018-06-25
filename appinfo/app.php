<?php

\OC::$server->getNavigationManager()->add(function () {
    $urlGenerator = \OC::$server->getURLGenerator();
    return [
        'id' => 'usage_amount',
        'order' => 10,
        'href' => $urlGenerator->linkToRoute('Usage_Amount.page.index'),
        'icon' => $urlGenerator->imagePath('usage_amount', 'icon.png'),
        'name' => \OC::$server->getL10N('usage_amount')->t('使用者用量'),
    ];
});