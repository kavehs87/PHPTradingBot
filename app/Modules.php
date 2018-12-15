<?php
/**
 * Created by PhpStorm.
 * User: kavehs
 * Date: 12/13/18
 * Time: 23:45
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed active
 * @property string config
 * @property mixed class
 */
class Modules extends Model
{
    protected $table = 'modules';
    protected $guarded = [];
    public static $description;
    public static $hasMenu = false;


    public static function factory($module)
    {
        return self::getModuleWithNameSpace($module)::where('class', $module)->first();
    }

    public function setConfig(Array $array)
    {
        $this->config = json_encode($array);
        $this->save();
    }

    public function setActive()
    {
        $this->active = true;
        $this->save();
    }

    public function setInactive()
    {
        $this->active = false;
        $this->save();
    }

    public static function install($module)
    {
        return self::create([
            'class' => $module,
            'active' => false,
            'config' => json_encode([])
        ]);
    }

    public function isActive()
    {
        return $this->active;
    }

    public function getConfig($key = null)
    {
        if (!empty($this->config)) {
            if ($key){
                $config = json_decode($this->config, true);
                if (isset($config[$key])){
                    return $config[$key];
                }
                else {
                    return null;
                }
            }
            return json_decode($this->config, true);
        }
        return false;
    }


    public static function sync()
    {

    }

    public static function getModules()
    {
        $modulesDirectory = app_path() . DIRECTORY_SEPARATOR . 'Modules';
        $modules = [];
        if ($handle = opendir($modulesDirectory)) {

            while (false !== ($entry = readdir($handle))) {

                if ($entry != "." && $entry != "..") {
                    if (strpos($entry,'.php') === false)
                        continue;
                    if (self::checkModule($entry)) {
                        $modules[] = self::sanitizeModuleName($entry);
                    }
                }
            }
            closedir($handle);
        }
        return $modules;
    }

    public static function checkModule($module)
    {
        $moduleFileName = $module;
        $module = self::sanitizeModuleName($module);
        $modulesDirectory = app_path() . DIRECTORY_SEPARATOR . 'Modules';
        $modulePath = $modulesDirectory . DIRECTORY_SEPARATOR . $moduleFileName;
        if (!file_exists($modulePath))
            return false;
        try {
            $moduleNameSpacedClass = "\\App\Modules\\" . $module;
            $module = new $moduleNameSpacedClass();
            if (!is_a($module, 'App\Modules'))
                return false;
        } catch (\Exception $exception) {
            return false;
        }

        return true;
    }

    public static function getModuleWithNameSpace($module)
    {
        $module = self::sanitizeModuleName($module);
        $moduleNameSpacedClass = "\\App\Modules\\" . $module;
        return $moduleNameSpacedClass;
    }

    public static function sanitizeModuleName($module)
    {
        if (strpos($module, '.') !== false) {
            return str_replace('.php', '', $module);
        }
        return $module;
    }

    public function getObject()
    {
        return self::factory($this->class);
    }

    public function getFactory()
    {
        return self::factory($this->class);
    }

    public static function getMenus()
    {
        $menus = [];
        $modules = self::where('active',1)->get();
        if ($modules->isEmpty())
            return false;

        foreach ($modules as $module) {
            $_module = $module->getObject();
            if (!empty($_module->menus())){
//                $menus[] = $_module->menus();
                $menus = array_merge($menus,$_module->menus());
            }
        }

        return $menus;
    }

    public static function getActiveModules()
    {
        $modules = self::where('active',1)->get();
        if ($modules->isEmpty())
            return false;

        return $modules;
    }

    /**
     * Hook for price daemon
     * @param $prices
     */
    public function onTick($prices)
    {

    }

    /**
     * Hook for Signal@updating method
     * @param $signal
     */
    public function onSignalReceived($signal)
    {

    }

    /**
     * Hooks for before selling event
     * doesn't sell if this function returns false;
     */
    public function beforeSell()
    {
        return true;
    }

    /**
     * Hooks for after selling event
     */
    public function afterSell()
    {

    }

    /**
     * Hooks for before buying event
     * doesn't buy if this function returns false;
     */
    public function beforeBuy()
    {
        return true;
    }

    /**
     * Hooks for after buying event
     */
    public function afterBuy()
    {

    }

    /**
     * Hook for Menu items
     */
    public function menus()
    {
        return [];
    }



}