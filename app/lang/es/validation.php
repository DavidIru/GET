<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used
	| by the validator class. Some of the rules contain multiple versions,
	| such as the size (max, min, between) rules. These versions are used
	| for different input types such as strings and files.
	|
	| These language lines may be easily changed to provide custom error
	| messages in your application. Error messages for custom validation
	| rules may also be added to this file.
	|
	*/

	"accepted"       => "El campo :attribute debe ser aceptado.",
	"active_url"     => "El campo :attribute no es una URL válida.",
	"after"          => "El campo :attribute debe ser una fecha después de :date.",
	"alpha"          => "El campo :attribute sólo puede contener letras.",
	"alpha_dash"     => "El campo :attribute sólo puede contener letras, números y guiones.",
	"alpha_num"      => "El campo :attribute sólo puede contener letras y números.",
	"array"          => "El campo :attribute debe ser un array.",
	"before"         => "El campo :attribute debe ser una fecha antes :date.",
	"between"        => array(
		"numeric" => "El campo :attribute debe estar entre :min y :max.",
		"file"    => "El campo :attribute debe estar entre :min y :max kilobytes.",
		"string"  => "El campo :attribute debe estar entre :min y :max caracteres.",
		"array"   => "El campo :attribute debe tener entre :min y :max elementos.",
	),
	"confirmed"      => "El campo :attribute confirmación no coincide.",
	"date"           => "El campo :attribute no es una fecha válida.",
	"date_format"    => "El campo :attribute no coincide con el formato :format.",
	"different"      => "El campo :attribute y :other deben ser diferentes.",
	"digits"         => "El campo :attribute debe tener :digits dígitos.",
	"digits_between" => "El campo :attribute debe tener entre :min y :max dígitos.",
	"email"          => "El formato del :attribute es inválido.",
	"exists"         => "El campo :attribute seleccionado es inválido.",
	"image"          => "El campo :attribute debe ser una imagen.",
	"in"             => "El campo :attribute seleccionado es inválido.",
	"integer"        => "El campo :attribute debe ser un entero.",
	"ip"             => "El campo :attribute debe ser una dirección IP válida.",
	"match"          => "El formato del campo :attribute es inválido.",
	"max"            => array(
		"numeric" => "El campo :attribute debe ser menor que :max.",
		"file"    => "El campo :attribute debe ser menor que :max kilobytes.",
		"string"  => "El campo :attribute debe ser menor que :max caracteres.",
		"array"   => "El campo :attribute debe tener menos de :max elementos.",
	),
	"mimes"          => "El campo :attribute debe ser un archivo de tipo :values.",
	"min"            => array(
		"numeric" => "El campo :attribute debe tener al menos :min.",
		"file"    => "El campo :attribute debe tener al menos :min kilobytes.",
		"string"  => "El campo :attribute debe tener al menos :min caracteres.",
		"array"   => "El campo :attribute debe tener al menos :min elementos.",
	),
	"not_in"         => "El campo :attribute seleccionado es inválido.",
	"numeric"        => "El campo :attribute debe ser un número.",
	"regex"          => "El campo :attribute tiene un formato inválido.",
	"required"       => "El campo :attribute es requerido",
	"required_if"      => "El campo :attribute es requerido cuando el campo :other es :value.",
	"required_with"    => "El campo :attribute es requerido con los valores :values.",
	"required_without" => "El campo :attribute es requerido sin los valores :values.",
	"same"           => "Los campos :attribute y :other deben coincidir.",
	"size"           => array(
		"numeric" => "El campo :attribute debe tener un tamaño de :size.",
		"file"    => "El campo :attribute debe tener :size kilobytes.",
		"string"  => "El campo :attribute debe tener :size caracteres.",
		"array"   => "El campo :attribute debe tener :size elementos.",
	),
	"unique"         => "El campo :attribute ya existe en la base de datos.",
	"url"            => "El formato de :attribute es inválido.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute_rule" to name the lines. This helps keep your
	| custom validation clean and tidy.
	|
	| So, say you want to use a custom validation message when validating that
	| the "email" attribute is unique. Just add "email_unique" to this array
	| with your custom message. The Validator will handle the rest!
	|
	*/

	'custom' => array(),

	/*
	|--------------------------------------------------------------------------
	| Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as "E-Mail Address" instead
	| of "email". Your users will thank you.
	|
	| The Validator class will automatically search this array of lines it
	| is attempting to replace the :attribute place-holder in messages.
	| It's pretty slick. We think you'll like it.
	|
	*/

	'attributes' => array(
		'agrupacion_id' => 'familia agrupación',
		'familia_id' => 'familia',
		'pass' => 'contraseña',
		'pass2' => 'contraseña repetida',
		'pass_ant' => 'contraseña antigua',
		'pass_ant' => 'contraseña antigua',
		'password' => 'contraseña',
		'subfamilia_id' => 'subfamilia',
		'username' => 'usuario'
	),

);