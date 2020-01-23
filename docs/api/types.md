# Component types

* [ComponentAbstract](#componentabstract)
  * [FormAbstract](#formabstract)
    * [MultilingualAbstract](#multilingualabstract)
    * [TemporalAbstract](#temporalabstract)
    * [UploadableAbstract](#uploadableabstract)
    * [CheckableAbstract](#checkableabstract)
    * [SelectableAbstract](#selectableabstract)
  * [SubmitAbstract](#submitabstract)
    * [ButtonAbstract](#buttonabstract)
  * [MediaAbstract](#mediaabstract)
    * [ImageAbstract](#imageabstract)
    * [VideoAbstract](#videoabstract)

## ComponentAbstract

**Methods**
  
| Signature | Required | Description |
|---|---|---|
| containerId(string $containerId): self | No | Set the component container id. |
| componentId(string $componentId): self | No | Set the component id. |
| containerClasses(array $containerClasses): self | No | Set the component container classes. |
| componentClasses(array $componentClasses): self | No | Set the component classes. |
| containerHtmlAttributes(array $containerHtmlAttributes): self | No | Set the component container html attributes. |
| componentHtmlAttributes(array $componentHtmlAttributes): self | No | Set the component html attributes. |

**Usage**

```php
<ComponentAbstract>
    ->containerId('container-id')
    ->componentId('component-id')
    ->containerClasses(['container', 'classes'])
    ->componentClasses(['component', 'classes'])
    ->containerHtmlAttributes(['container', 'html', 'attributes'])
    ->componentHtmlAttributes(['component', 'html', 'attributes']);
```

## FormAbstract

**Inheritance :** [ComponentAbstract](#componentabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| name(string $name): self | Yes | Set the component input name tag. |
| model(Model $model): self | No | Set the component associated model. |
| value($value): self | No | Set the component input value. |
| prepend(?string $html): self | No | Prepend html to the component input group. Set false to hide it. |
| append(?string $html): self | No | Append html to the component input group. Set false to hide it. |
| label(?string $label): self | No | Set the component input label. Default value : `__('validation.attributes.' .$name)`. |
| labelPositionedAbove(bool $positionedAbove = true): self | No | Set the label above-positioning status. If not positioned above, the label will be positioned under the input (may be useful for bootstrap 4 floating labels). |
| placeholder(?string $placeholder): self | No | Set the component input placeholder. Default value : `$label`. |
| caption(?string $caption): self | No | Set the component caption. |
| displaySuccess(?bool $displaySuccess = true): self | No | Set the component input validation success display status. |
| displayFailure(?bool $displayFailure = true): self | No | Set the component input validation failure display status. |

**Notes**

* The method `value()` accepts a closure to define the component value. To provide a fallback in case of multilingual use, the `$locale` argument can be used, which returns the current locale in case of monolingual component.

**Usage**

```php
<FormAbstract>
    // inherits ComponentAbstract methods
    ->name('email')
    ->model($user)
    ->value('john.doe@domain.com')
    ->prepend('<i class="fas fa-hand-pointer"></i>')
    ->append('<i class="fas fa-hand-pointer"></i>')
    ->label('Email')
    ->labelPositionedAbove()
    ->placeholder('Set your e-mail')
    ->caption('Set your caption here.')
    ->displaySuccess(false)
    ->displayFailure(false);
```

**Components**

* [Input e-mail](./components.md#input-e-mail)
* [Input password](./components.md#input-password)
* [Input URL](./components.md#input-url)
* [Input tel](./components.md#input-tel)
* [Input number](./components.md#input-number)
* [Input color](./components.md#input-color)

## MultilingualAbstract

**Inheritance :** [FormAbstract](#formabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| locales(array $locales): self | No | Set the component input language locales to handle. |
| value(Closure $value): self | No | Set the component input value. The value has to be set from this closure result : `->value(function($locale){})`. |

**Notes**

* Each multilingual form component will behave as a monolingual form component as long as the `->locales()` method is not used or as long as only one locale is declared.
* The use of the `->locales()` method will replicate the component for each locale keys you declared.
  * For example, if you declare the `fr` and `en` locale keys for a text input component with the `title` attribute, you will get two `Title (FR)` and `Title (EN)` generated text input components.
* Each multilingual component provides an extra `data-locale="<locale>"` attribute to help with eventual javascript treatments.
* You can use your own multilingual `Resolver` by replacing the path defined in the `config('bootstrap-components.form.multilingualResolver')`, allowing you to customize your multilingual form components localization behaviour :
  * The default locales to handle (by default `[]`).
  * The component localized `name` attribute resolution (default : `$name[$locale]`.
  * The component localized old value resolution in case of errors (default : `old($name)[$locale]`).
  * The component localized model value resolution (default : `$model->{$name}[$locales]`).
  * The component localized error message bag key resolution, used for the error message extraction and for the validation class generation (default : `$name . $locale`).
  * The component error message resolution, in order to correctly display the localized attribute name (default : transform `Dummy __('validation.attributes.name.en) error message` into `Dummy __('validation.attributes.name) (EN) error message.`.

**Usage**

```php
<MultilingualAbstract>
    // inherits FormAbstract methods
    ->locales(['fr', 'en']) 
    ->value(function($locale){ return $name[$locale]; });
```

**Components**

* [Input text](./components.md#input-text)
* [Textarea](./components.md#textarea)

## TemporalAbstract

**Inheritance :** [FormAbstract](#formabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| format(string $format): self | Yes | Set the temporal format. |

**Usage**

```php
<TemporalAbstract>
    // inherits FormAbstract methods
    ->format('Y-m-d H:i');
```

**Components**

* [Input date](./components.md#input-date)
* [Input time](./components.md#input-time)
* [Input datetime](./components.md#input-datetime)

## UploadableAbstract

**Inheritance :** [FormAbstract](#formabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| uploadedFile(Closure $uploadedFile): self | No | Allows to set html or another component to render the uploaded file. |
| showRemoveCheckbox(bool $showRemoveCheckbox = true, string $removeCheckboxLabel = null): self | No | Show the file remove checkbox option (will appear only if an uploaded file is detected). Default value : `config('bootstrap-components.file.showRemoveCheckbox')`. The remove checkbox label can be precised with the second parameter, by default, it will take the following value : `__('Remove') . ' ' . $name` |

**Usage**

```php
<UploadableAbstract>
    // inherits FormAbstract methods
    ->uploadedFile(function(){
        return '<div>Some HTML</div>';
    })
    ->showRemoveCheckbox(true, 'Remove this file');
```

**Components**

* [Input file](./components.md#input-file)

## CheckableAbstract

**Inheritance :** [FormAbstract](#formabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| checked(bool $checked = true): self | No | Set the component checked status. |

**Notes**

* the inherited FormAbstract `->labelPositionedAbove()` method has no effect with this component type.

**Usage**

```php
<CheckableAbstract>
    // inherits FormAbstract methods
    ->checked();
```

**Components**

* [Input checkbox](./components.md#input-checkbox)
* [Input toggle](./components.md#input-toggle)
* [Input radio](./components.md#input-radio)

## SelectableAbstract

**Inheritance :** [FormAbstract](#formabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| options(iterable $optionsList, string $optionValueField, string $optionLabelField): self | No | Set the options list (array or models collection) and declare which fields should be used for the options values and labels. |
| selected(string $fieldToCompare, $valueToCompare): self | No | Choose which option should be selected, declaring the field and the value to compare with the declared options list. |
| multiple(bool $multiple = true): self | No | Set the select multiple mode. |

**Notes**
* in `single` mode, the selected() method second attribute only accept a string or an integer.
* in `multiple` mode, the selected() method second attribute only accept an array.

**Usage**

```php
<SelectableAbstract>
    // inherits FormAbstract methods
    ->options(collect([
        ['id' => 1, 'title' => 'Item 1'],
        ['id' => 2, 'title' => 'Item 2'],
    ]), 'id', 'title')
    ->selected('id', 1)
    // or ->selected('id', [1]) in multiple mode
    ->multiple();
```

**Components**

* [Select](./components.md#select)

## SubmitAbstract

**Inheritance :** [ComponentAbstract](#componentabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| prepend(?string $html): self | No | Prepend html to the button component label. Set false to hide it. |
| append(?string $html): self | No | Append html to the button component label. Set false to hide it. |
| label(string $label): self | No | Set the button component label. |

**Usage**

```php
<SubmitAbstract>
    // inherits ComponentAbstract methods
    ->label('Back to the users list')
    ->prepend('<i class="fas fa-hand-pointer"></i>')
    ->append('<i class="fas fa-hand-pointer"></i>');
```

**Components**

* [Submit](./components.md#submit)
* [Submit validate](./components.md#submit-validate)
* [Submit create](./components.md#submit-create)
* [Submit update](./components.md#submit-update)

## ButtonAbstract

**Inheritance :** [SubmitAbstract](#submitabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| url(string $url): self | No | Set the button component url. |
| route(string $route, array $params = []): self | No | Set the button component route. |

**Usage**

```php
<link>
    // inherits SubmitAbstract methods
    ->url('https://website.com/admin/users')
    ->route('users.index');
```

**Components**
* [Button](./components.md#button)
* [Button link](./components.md#button-link)
* [Button back](./components.md#button-back)
* [Button cancel](./components.md#button-cancel)

## MediaAbstract

**Inheritance :** [ComponentAbstract](#componentabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| label(?string $label): self | No | Set the component label. |
| src(string $src): self | No | Set the component src attribute. |
| caption(?string $caption): self | No | Set the component caption. |

**Usage**

```php
<MediaAbstract>
    // inherits ComponentAbstract methods
    ->src('https://yourapp.fr/public/media/audio.mp3');
```

**Components**

* [Audio](./components.md#audio)

## ImageAbstract

**Inheritance :** [MediaAbstract](#mediaabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| alt(string $alt): self | No | Define the image component alt html tag. |
| width(int $width): self | No | Define the component image html tag width. |
| height(int $height): self | No | Define the component image html tag height. |
| linkUrl(string $linkUrl): self | No | Set the image component link URL. |
| linkTitle(string $linkTitle): self | No | Set the image component link title. |
| linkId(string $linkId): self | No | Set the image component link id. |
| linkClasses(array $linkClasses): self | No | Set the image component link classes. Default value : `config('bootstrap-components.media.image.classes.link')`. |
| linkHtmlAttributes(array $linkHtmlAttributes): self | No | Set the image component link html attributes. Default value : `config('bootstrap-components.media.image.htmlAttributes.link')`. |

**Usage**

```php
<ImageAbstract>
    // inherits MediaAbstract methods
    ->alt('Image alt attribute')
    ->width(250)
    ->height(150)
    ->link('https://yourapp.fr/public/media/image-zoom.jpg', 'Preview this image')
    ->linkId('link-id')
    ->linkComponentClasses(['link', 'component', 'classes'])
    ->linkHtmlAttributes(['link', 'component', 'classes']);
```

**Components**

* [Image](./components.md#image)

## VideoAbstract

**Inheritance :** [MediaAbstract](#mediaabstract)

**Methods**

| Signature | Required | Description |
|---|---|---|
| poster(string $poster): self | No | Set the video component poster. |

**Usage**

```php
<VideoAbstract>
    // inherits MediaAbstract methods
    ->poster('https://yourapp.fr/public/media/poster.jpg');
```

**Components**

* [Video](./components.md#video)