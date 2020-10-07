<?php

namespace LaminasGOT_TWIG\TwigExtensions {

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFilter;
    use Twig\TwigFunction;
    use Twig\TwigTest;

    /**
     * Class LayoutTwigExtension
     * @package GraphicObjectTemplating\TwigExtensions
     *
     * fonctions :
     * -----------
     * getclass         : retourne la classe associé à l'objet
     * gettype          : retourne le type de variable
     * arrayexception   : restitue le tableau d'exception Php généré par une erreur
     * substr           : sous découpage d'une chaîne de caractères
     * strpos           : restitue la position d'uncaractère ou sous chaîne dans chaîne de caractères
     * instring         : précise l'existance d'un caractère ou sous chaîne ou non dans une chaîne de caractères
     * arraykeyexists    : recherche l'existance d'une clé d'accès dans un tableau
     * implode          : génération d'une chaîne de caractères à partir d'un tableau lié par un délimiteur
     *
     * tests :
     * -------
     * instanceof       : valide ou non que l'obvjet est une instance d'une classe
     * typeof           : valide ou non que l'objet (variable) est d'un type précis
     *
     * filtres :
     * ---------
     * update           : met à jour ou complète un tableau
     */
    final class LayoutTwigExtension extends AbstractExtension
    {
        public function getFunctions(): array
        {
            return [
                new TwigFunction('gettype', 'lte_get_type'),
                new TwigFunction('getclass', 'lte_get_class'),
                new TwigFunction('arrayexception', 'lte_array_exception'),
                new TwigFunction('substr', 'lte_substr'),
                new TwigFunction('strpos', 'lte_strpos'),
                new TwigFunction('instring', 'lte_instring'),
                new TwigFunction('arraykeyexists', 'lte_array_key_exists'),
                new TwigFunction('implode', 'lte_implode')
            ];
        }

        public function getTests(): array
        {
            return [
                new TwigTest('instanceof', 'lte_instance_of'),
                new TwigTest('typeof', 'lte_type_of')
            ];
        }

        public function getFilters(): array
        {
            return [
                new TwigFilter('update', 'lte_update')
            ];
        }

        public function getName() : string
        {
            return 'layout_twig_extension';
        }
    }
}

namespace {

    use Twig\Error\RuntimeError as TwigErrorRuntime;

    /** LTE Functions */

    function lte_get_type($object = null)
    {
        return gettype($object);
    }

    function lte_get_class($object = null)
    {
        if (is_object($object) && $object !== null) {
            return get_class($object);
        }
        return "noClass";
    }

    function lte_array_exception(Exception $exception)
    {
        $retArray = [];
        $retArray['File'] = $exception->getFile();
        $retArray['Line'] = $exception->getLine();
        $retArray['Message'] = $exception->getMessage();
        $retArray['TraceAsString'] = $exception->getTraceAsString();

        $tmpException = $exception->getPrevious();
        $arrayPrevious = [];
        while ($tmpException) {
            $item = [];
            $item['Class'] = get_class($tmpException);
            $item['File'] = $tmpException->getFile();
            $item['Line'] = $tmpException->getLine();
            $item['Message'] = $tmpException->getMessage();
            $item['TraceAsString'] = $tmpException->getTraceAsString();

            $arrayPrevious[] = $item;
            $tmpException = $tmpException->getPrevious();
        }

        $retArray['Previous'] = $arrayPrevious;

        return $retArray;
    }

    function lte_substr(string $str, int $start = null, int $len = null)
    {
        if (!$start) $start = 0;
        if ($len > 0) {
            return substr($str, $start, $len);
        }
        return substr($str, $start);
    }

    function lte_strpos(string $str, $search, int $offset = null)
    {
        if ($offset > 0) {
            return strpos($str, $search, $offset);
        }
        return strpos($str, $search);
    }

    function lte_instring(string $var1, string $var2)
    {
        return (strpos($var2, $var1) !== false) ? true : false;
    }

    function lte_array_key_exists($key, array $array)
    {
        return array_key_exists($key, $array);
    }

    function lte_implode(string $delimiter, array $array)
    {
        return implode($delimiter, $array);
    }

    /** LTE Tests */
    function lte_instance_of($object, string $class)
    {
        return (new ReflectionClass($class))->isInstance($object);
    }

    function lte_type_of($var, string $type_test = null)
    {
        switch ($type_test) {
            default:
                return false;
                break;
            case 'array':
                return is_array($var);
                break;
            case 'bool':
                return is_bool($var);
                break;
            case 'float':
                return is_float($var);
                break;
            case 'int':
                return is_int($var);
                break;
            case 'numeric':
                return is_numeric($var);
                break;
            case 'object':
                return is_object($var);
                break;
            case 'scalar':
                return is_scalar($var);
                break;
            case 'string':
                return is_string($var);
                break;
            case 'datetime':
                return ($var instanceof DateTime);
                break;
        }
    }

    /** LTE Filters */

    function lte_update($arr1, $arr2)
    {
        if ($arr1 instanceof Traversable) {
            $arr1 = iterator_to_array($arr1);
        } elseif (!is_array($arr1)) {
            throw new TwigErrorRuntime(sprintf('The update filter only works with arrays or "Traversable", got "%s" as first argument.', gettype($arr1)));
        }

        if ($arr2 instanceof Traversable) {
            $arr2 = iterator_to_array($arr2);
        } elseif (!is_array($arr2)) {
            throw new TwigErrorRuntime(sprintf('The update filter only works with arrays or "Traversable", got "%s" as second argument.', gettype($arr2)));
        }

        foreach ($arr2 as $key => $value) {
            $arr1[$key] = $value;
        }
        return $arr1;
    }
}