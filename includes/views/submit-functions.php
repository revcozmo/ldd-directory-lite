<?php

function ld_split_file_into_array( $arrfile ) {

    if ( !file_exists( $arrfile ) )
        return false;

    $lines = file( $arrfile );
    $data = array();

    foreach ( $lines as $line ) {
        $kv = explode( ',', $line );
        $data[ $kv[0] ] = $kv[1];
    }

    return $data;
}


function ld_get_subdivision_array( $subdivision ) {

    $subdivision_file = LDDLITE_PATH . '/includes/views/select/subdivision.' . $subdivision . '.inc';

    return ld_split_file_into_array( $subdivision_file );
}

function ld_get_country_array() {

    $country_file = LDDLITE_PATH . '/includes/views/select/countries.inc';

    return ld_split_file_into_array( $country_file );
}


function ld_dropdown_subdivision( $subdivision )
{


    $data = ld_get_subdivision_array( $subdivision );

    if ( !$data )
        return '<input id="subdivision" name="ld_s_subdivision" type="text" tabindex="9" required>';

    $output = '<select id="subdivision" name="ld_s_subdivision" tabindex="9" required>';

    foreach ( $data as $line ) {
        $output .= '<option name="' . $line[0] . '"';
        $output .= '>' . $line[1] . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ld_dropdown_country()
{

    $countries = ld_get_country_array();

    if ( !$countries )
        return '<input id="country" name="ld_s_country" type="text" tabindex="7" required>';

    $output = '<select id="country" name="ld_s_country" tabindex="7" required>';

    foreach ( $countries as $code => $name ) {
        $output .= '<option value="' . $code . '"';
        $output .= '>' . $name . '</option>';
    }

    $output .= '</select>';

    return $output;

}


function ld_sanitize_phone( $number ) {
    return preg_replace( '/[^0-9+]/', '', $number );
}