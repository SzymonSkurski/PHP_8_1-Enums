<?php

//my legacy code PHP 7.1^

CONST FORMATION_KIND_ATT = 'att'; //attack formation
CONST FORMATION_KIND_DEF = 'def'; //defence formation
CONST FORMATION_KIND_TEMP = 'temp'; //temporary formation
CONST FORMATION_KIND_DUNGEONS = 'dungeons'; //temporary formation
const FORMATION_KINDS = [
    FORMATION_KIND_ATT,
    FORMATION_KIND_DEF,
    FORMATION_KIND_TEMP,
    FORMATION_KIND_DUNGEONS,
];

/**
 * @param string $kind
 * @throws Exception
 */
function doSomethingWithFormation(string $kind): void
{
    checkFormationKind($kind); //will throw exception
    //formation is valid, do something
    //...
    print_r("\r\nvalid kind:{$kind}");
}

/**
 * @param string $kind
 * @return bool
 */
function isSupportedFormationKind(string $kind): bool
{
    return in_array($kind, FORMATION_KINDS, true);
}

/**
 * @param string $kind
 * @throws Exception
 */
function checkFormationKind(string $kind): void
{
    if (!isSupportedFormationKind($kind)) {
        throw new Exception('unsupported formation kind:' . $kind); //consider custom exception for this
    }
}

//use new feature Enums PHP 8.1
enum FormationKind {
    case att;
    case def;
    case temp;
    case dungeons;
}
//how to access this enum?
//FormationKind::cases() - array of all enum
//FormationKind::att->name // (string) att
//backed enums could also have values access via ->value
//it is great alternative for constants
//https://php.watch/versions/8.1/enums more about

function doSomethingWithFormation_New(FormationKind $kind): void
{
    $kind = $kind->name;
    //formation kind is valid, do something
    //...
    print_r("\r\nvalid kind:{$kind}");
}

//and test it
function testDoSomethingWithFormation(array $kinds): void {
    foreach ($kinds as $kind) {
        try {
            doSomethingWithFormation($kind);
        } catch (Throwable $e) {
            $msg = $e->getMessage();
            $kind = json_encode($kind);
            print_r("\r\n{$kind} error: {$msg}");
        }
    }
}

$inValid = ['nuts', 123, json_decode('{"kind":"att"}'), ['kind' => 'def']];
print_r("\r\nvalid legacy\r\n");
testDoSomethingWithFormation(FORMATION_KINDS);
print_r("\r\ninvalid legacy\r\n");
testDoSomethingWithFormation($inValid);
print_r("\r\nvalid new\r\n");
foreach (FormationKind::cases() as $case) {
    try {
        doSomethingWithFormation_New($case); //enum(FormationKind::att)
    } catch (Throwable $e) {
        $msg = $e->getMessage();
        $kind = json_encode($case);
        print_r("\r\n{$kind} error: {$msg}");
    }
}
print_r("\r\ninvalid new\r\n");
foreach ($inValid as $invalid) {
    try {
        doSomethingWithFormation_New($invalid);
    } catch (Throwable $e) {
        $msg = $e->getMessage();
        $kind = json_encode($invalid);
        print_r("\r\n{$kind} error: {$msg}");
    }
}

//results
//PHP 7.4.7
//PHP Parse error:  syntax error, unexpected 'FormationKind' (T_STRING)

//PHP 8.1.2
//valid legacy
//
//valid kind:att
//valid kind:def
//valid kind:temp
//valid kind:dungeons
//invalid legacy
//
//"nuts" error: unsupported formation kind:nuts
//123 error: unsupported formation kind:123
//{"kind":"att"} error: doSomethingWithFormation(): Argument #1 ($kind) must be of type string, stdClass given, called in ...index.php on line 68
//{"kind":"def"} error: doSomethingWithFormation(): Argument #1 ($kind) must be of type string, array given, called in ...index.php on line 68
//valid new
//
//valid kind:att
//valid kind:def
//valid kind:temp
//valid kind:dungeons
//invalid new
//
//"nuts" error: doSomethingWithFormation_New(): Argument #1 ($kind) must be of type FormationKind, string given, called in ...index.php on line 95
//123 error: doSomethingWithFormation_New(): Argument #1 ($kind) must be of type FormationKind, int given, called in ...index.php on line 95
//{"kind":"att"} error: doSomethingWithFormation_New(): Argument #1 ($kind) must be of type FormationKind, stdClass given, called in ...index.php on line 95
//{"kind":"def"} error: doSomethingWithFormation_New(): Argument #1 ($kind) must be of type FormationKind, array given, called in ...index.php on line 95
