// Function to sanitize the attribute name
function sanitizeString(phpVariableName) {
  // remove spaces from attribute name
  phpVariableName = phpVariableName.replace(/\s+/g, "");

  // remove special characters from attribute name
  phpVariableName = phpVariableName.replace(/[^\w\s]/gi, "");

  // remove quotes from attribute name
  phpVariableName = phpVariableName.replace(/['"]+/g, "");

  // remove the underscores
  phpVariableName = phpVariableName.replace(/_+/g, "");

  // Remove numbers from the beginning of attribute name
  phpVariableName = phpVariableName.replace(/^\d+/, "");

  let reservedKeywords = [
    "__halt_compiler",
    "abstract",
    "and",
    "array",
    "as",
    "break",
    "callable",
    "case",
    "catch",
    "class",
    "clone",
    "const",
    "continue",
    "declare",
    "default",
    "die",
    "do",
    "echo",
    "else",
    "elseif",
    "empty",
    "enddeclare",
    "endfor",
    "endforeach",
    "endif",
    "endswitch",
    "endwhile",
    "eval",
    "exit",
    "extends",
    "final",
    "finally",
    "for",
    "foreach",
    "function",
    "global",
    "GLOBALS",
    "goto",
    "if",
    "implements",
    "include",
    "include_once",
    "instanceof",
    "insteadof",
    "interface",
    "isset",
    "list",
    "namespace",
    "new",
    "or",
    "print",
    "private",
    "protected",
    "public",
    "require",
    "require_once",
    "return",
    "static",
    "switch",
    "throw",
    "trait",
    "try",
    "unset",
    "use",
    "var",
    "while",
    "xor",
    "yield",
    "__CLASS__",
    "__DIR__",
    "__FILE__",
    "__FUNCTION__",
    "__LINE__",
    "__METHOD__",
    "__NAMESPACE__",
    "__TRAIT__",
    "int",
    "float",
    "bool",
    "string",
    "true",
    "false",
    "null",
    "void",
    "iterable",
    "object",
    "resource",
    "mixed",
    "numeric",
    "array",
    "callable",
    "static",
    "self",
    "parent",
    "dec",
    "double",
  ];

  // Check if the attribute name is a reserved keyword
  if (reservedKeywords.includes(phpVariableName)) {
    alert(
      'The name "' +
        phpVariableName +
        '" is a reserved keyword. Please use a different name.'
    );
    return;
  }

  // Check if the attribute name is empty
  if (phpVariableName.length === 0) {
    alert("Please enter a valid name.");
    return;
  }

  return phpVariableName;
}
