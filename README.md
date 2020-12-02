# Jsonld

![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/exinfinite/jsonld)
![GitHub release (latest SemVer)](https://img.shields.io/github/v/release/exinfinite/jsonld)
![Packagist Version](https://img.shields.io/packagist/v/exinfinite/jsonld)
![Packagist Downloads](https://img.shields.io/packagist/dt/exinfinite/jsonld)
![GitHub](https://img.shields.io/github/license/exinfinite/jsonld)

## 安裝

```php
composer require exinfinite/jsonld
```

## 使用

### 初始化

```php
$jsonld = new Exinfinite\Jsonld("site name", "site url", "full url of site logo");
$jsonld->setTimezone("timezone");//default:"Asia/Taipei"
```

### BreadcrumbList

```php
$jsonld->breadcrumb([
    'page1_url' => 'page1_title',
    'page2_url' => 'page2_title',
    'page3_url' => 'page3_title',
    ...
]);
```

### SearchAction

```php
$jsonld->search("full path of search url", "query_param");
```

### NewsArticle

```php
$jsonld->article("page title", "page description", ["page thumbnail", "page thumbnail"], "page published date", "page modified date");
```

## 最終輸出

```php
$jsonld->render();
```