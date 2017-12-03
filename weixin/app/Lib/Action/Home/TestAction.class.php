<?php
/**
 * Created by PhpStorm.
 * User: alexzhuub
 * Date: 14-11-2
 * Time: 上午11:21
 */
class TestAction extends Action {
    public function testwark(){
        $x='31.074533';
        $y='121.407951';
        import('Home.Action.MapAction');
        $mapAction = new MapAction();
        $companyid=1;
        if (!$companyid) {
            $companyid = 1;
        }
        $aa= $mapAction->nearest_jx($x, $y);
        var_dump($aa);

}

}