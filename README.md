# simple-excel-parser
A simple excel parser to parse each row into associative arrays.

## When to use this?
- Your excel file has the header at the first row, and data at the rest rows.
- You want to map the header titles to database keys.
- You want to map the data values to database enums.

## Example
You want to parse the excel with the following format.

| Name   | Age | Gender | Phone 1      | Phone 2      |
|--------|-----|--------|--------------|--------------|
| Bill   | 18  | Male   | 222-222-2222 | 777-777-7777 |
| Peter  | 25  | Male   | 222-333-4444 | 555-666-7777 |
| Monica | 31  | Female | 666-666-6666 | 555-555-5555 |

And you want to map the header titles to database keys, and map genders to `'m'` or `'f'`.

    $parser = new Jhesyong\Excel\Parser;

    // Map 'Name' to 'name', 'Age' to 'age', and 'Gender' to 'gender'.
    $parser->addHeader('Name', 'name');
    $parser->addHeader('Age', 'age');

    // Map the values: 'm' is for 'Male', and 'f' is for 'Female'
    $parser->addHeader('Gender', 'gender')->withOptions(['m' => 'Male', 'f' => 'Female']);

    // You can use a regulare expression and pass a callback to map multiple header titles.
    // In this case, 'Phone 1' will be mapped to 'phone_1'
    $parser->addHeader('/^Phone \d+$/', function($title)
    {
        return str_replace(' ', '_', strtolower($title));
    });

    // Load the file and start to parse. Pass a callback function
    // and it will be called for each row.
    $parser->loadFile('filename.xlsx')->parse(function($data)
    {
        // Data will be an associative array.
	    var_dump($data);
    });

## FAQ

### I have multiple titles of the same text, but I want to map them to different keys.
You can add headers with the same title but different keys in the desired order.

    // The first 'Phone' will be mapped to 'phone_1',
    // and the second 'Phone' will be mapped to 'phone_2'
    $parser->addHeader('Phone', 'phone_1');
    $parser->addHeader('Phone', 'phone_2');

### I want to use the title as the key
You can omit the second argument.

    // 'Phone' will be mapped to 'Phone'
    $parser->addHeader('Phone');

### I want to pre-define the header mapping and the options.
You can extends the class and write your rules in the constructor.
