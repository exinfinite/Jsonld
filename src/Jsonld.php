<?php
namespace Exinfinite;
class Jsonld {
    protected $tpl = '<script type="application/ld+json">%s</script>';
    protected $json = [];
    protected $context = "http://schema.org";
    protected $timezone = "Asia/Taipei";
    public function __construct($site_name, $site_url, $site_logo) {
        $this->site_name = $site_name;
        $this->site_url = $site_url;
        $this->site_logo = $site_logo;
        $this->website();
        $this->organization();
    }
    public function setTimezone($timezone) {
        $this->timezone = $timezone;
    }
    protected function push($key, $data) {
        $this->json[$key] = $data;
    }
    public function website() {
        $this->push('website', [
            "@context" => $this->context,
            "@type" => "WebSite",
            "name" => "{$this->site_name}",
            "url" => "{$this->site_url}",
        ]);
    }
    public function organization() {
        $this->push('organization', [
            "@context" => $this->context,
            "@type" => "Organization",
            "url" => "{$this->site_url}",
            "logo" => "{$this->site_logo}",
        ]);
    }
    public function breadcrumb($item_list = []) {
        $json_key = 'breadcrumb' . md5(serialize($item_list));
        if (!array_key_exists($json_key, $this->json) || !is_array($this->json[$json_key])) {
            $this->push($json_key, []);
        }
        $idx = 1;
        $item_list_tmp = [];
        $list_item = function ($idx, $url, $name) use (&$item_list_tmp) {
            array_push($item_list_tmp, [
                "@type" => "ListItem",
                "position" => (int) $idx,
                "item" => [
                    "@id" => "{$url}",
                    "name" => "{$name}",
                ],
            ]);
        };
        $list_item($idx, $this->site_url, $this->site_name);
        foreach ($item_list as $url => $name) {
            $list_item(++$idx, $url, $name);
        }
        $this->push($json_key, [
            "@context" => $this->context,
            "@type" => "BreadcrumbList",
            "itemListElement" => $item_list_tmp,
        ]);
    }
    public function search($uri, $param) {
        if (!is_string($param) || trim($param) == '') {
            return;
        }
        $this->push('search', [
            "@context" => $this->context,
            "@type" => "WebSite",
            "url" => "{$this->site_url}",
            "potentialAction" => [
                "@type" => "SearchAction",
                "target" => implode('?', [$uri, "{$param}={{$param}}"]),
                "query-input" => "required name={$param}",
            ],
        ]);
    }
    public function article($title, $description, array $images, $publish_date, $modified_date) {
        $timezone = new \DateTimeZone($this->timezone);
        $this->push('article', [
            "@context" => $this->context,
            "@type" => "NewsArticle",
            "headline" => $title,
            "description" => $description,
            "image" => $images,
            "datePublished" => (new \DateTime($publish_date))->setTimezone($timezone)->format('c'),
            "dateModified" => (new \DateTime($modified_date))->setTimezone($timezone)->format('c'),
        ]);
    }
    public function render() {
        echo implode("\n",
            array_reduce($this->json, function ($container, $context) {
                array_push($container, sprintf($this->tpl, json_encode($context)));
                return $container;
            }, [])
        );
    }
}
