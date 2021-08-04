<?php

/**
 * Проверяет можно ли показывать рекламу и вернет её если да
 */
final class statusTinyAddService
{
    /**
     * @return array
     * @throws waException
     */
    public function getAd(): array
    {
        $_tinyAds = [];

        $_webasyst_base_url = (wa()->getLocale() === 'ru_RU')
            ? 'https://www.webasyst.ru/'
            : 'https://www.webasyst.com/';
        $_whichUI = (wa()->whichUI() == '1.3') ? '1' : '2'; //utm

        if (wa()->appExists('pocketlists')
            && empty(wa('pocketlists')->getConfig()->getPluginInfo('pro'))
            && wa()->getLocale() === 'ru_RU'
        ) {
            $_tinyAds[] = [
                'adtype' => 'plugin',
                'heading' => _w('Promocode'),
                'appurl' => $_webasyst_base_url . 'store/plugin/pocketlists/pro/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa' . $_whichUI,
                'buyurl' => $_webasyst_base_url . 'buy/store/5045/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2pro_upgrade_wa' . $_whichUI,
                'image' => wa()->getAppStaticUrl() . 'img/pl2ad-pro-plugin.png',
                'title' => 'Pocket Lists PRO',
                'subtitle' => 'Поможет поднять обработку заказов на 80 уровень.',
                'teaser' => 'Промокод на автоматизацию Shop-Script &rarr; Pocket Lists.',
                'body' => '<strong>Главная фишка плагина — автоматическое создание задач менеджерам при действиях с заказами.</strong> В обработку, отправлен, возврат — при каждом подобном действии с заказами нужным сотрудникам будут автоматически ставиться задачи согласно вашим настройкам. Не пропустите ни одной продажи!',
                'promocode' => wa()->whichUI() == '1.3' ? 'B87K2IZFCZ' : '9UVHYK63V8',
                'discount' => '20',
            ];
        }

        if (!wa()->appExists('tasks')) {
            $_tinyAds[] = [
                'adtype' => 'app',
                'heading' => _w('More apps by 1312 Inc.'),
                'appurl' => $_webasyst_base_url . 'store/app/tasks/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa' . $_whichUI,
                'buyurl' => $_webasyst_base_url . 'buy/store/1811/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_tasksapp_wa' . $_whichUI,
                'image' => wa()->getAppStaticUrl() . 'img/pl2ad-tasks-app-144.png',
                'title' => _w('Teamwork'),
                'subtitle' => _w('When tasks become bigger and more complex.'),
                'teaser' => _w('Promocode for our flagship Webasyst app.'),
                'body' => '<strong>' . _w('Our flagship app.') . '</strong>' . ' ' .
                    _w(
                        'Amazing companion/upgrade for Pocket Lists when it’s time for real collaboration on <em>bigger and more complex tasks</em>. Assignments, task statuses, deadlines, kanban board, more — the app help bringing the order to any complex teamwork.'
                    ),
                'promocode' => 'NCGR5G9ZUE',
                'discount' => '15',
            ];
        }

        if (!wa()->appExists('cash')) {
            $_tinyAds[] = [
                'adtype' => 'app',
                'heading' => _w('More apps by 1312 Inc.'),
                'appurl' => $_webasyst_base_url . 'store/app/cash/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_wa' . $_whichUI,
                'buyurl' => $_webasyst_base_url . 'buy/store/5136/?utm_source=pl2webasyst&utm_medium=inapp_tiny_ad&utm_campaign=1312_inapp_pl2webasyst_cashapp_wa' . $_whichUI,
                'image' => wa()->getAppStaticUrl() . 'img/pl2ad-cash-app-144.png',
                'title' => _w('Cash Flow'),
                'subtitle' => _w('Forecasts and saves your business money.'),
                'teaser' => _w('Promocode for managing money the smarter way.'),
                'body' => '<strong>' . _w('Forecasts and saves your money.') . '</strong>' . ' ' .
                    _w(
                        'Shows exact cash on hand balance for any date in the future. This app could have been a <em>life saver</em> for most businesses which did not survive a cash gap because of not knowing it’s coming.'
                    ),
                'promocode' => 'Z0J7OV1AHH',
                'discount' => '15',
            ];
        }

        //show random tiny
        return $_tinyAds ? $_tinyAds[array_rand($_tinyAds)] : [];
    }
}
