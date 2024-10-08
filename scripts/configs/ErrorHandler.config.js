////////////////////////////////////////////////////////////
/**
 **************  ERROR HANDLER CONFIG FILE
 */
////////////////////////////////////////////////////////////
                    /** FORM */
////////////////////////////////////////////////////////////

/**
 * Configurate form.id element to avoid checking
 * @module ErrorHandler
 * @type {String}
 */
export const formIDToAvoidChecking = 'search-form'
/**
 * Configurate form submit button ID to target and listen
 * @type {String}
 */
export const formButton = '#submit'

////////////////////////////////////////////////////////////
                    /** MESSAGES */
////////////////////////////////////////////////////////////

/**
 * Configurate invalid email error message
 * @type {String}
 */
export const invalidEmailMessage = `Votre email est invalide 
                exemple valide : monEmail@mail.fr`
/**
 * Configurate password invalid error message
 * @type {String}
 */
export const invalidPwMessage = "Votre mot de passe n'est pas assez fort"
/**
/**
 * Configurate password invalid error message
 * @type {String}
 */
export const notIdenticalPasswords = 'Vos mots de passes ne sont pas identiques'
/**
 * Configurate password cannot be same as username message
 * @type {String}
 */
export const pwCannotBeUsername = 'Votre mot de passe doit être différent de votre identifiant'
/**
 * Configurate no space allowed error message
 * @type {String}
 */
export const noSpaceAllowedMessage = 'Veuillez ne pas utiliser d\'espace'
/**
 * Configurate only integer allowed error message
 * @type {String}
 */
export const notANumberError = 'Seuls les nombres sont autorisés'
/**
 * Configurate only integer allowed error message
 * @type {String}
 */
export const wrongNumber = 'Seuls les nombres au-dessus de 0 sont autorisés'
/**
 * Configurate empty inputs error message
 * @type {String}
 */
export const emptyAlert = 'Un ou plusieurs champs doivent être renseignés'

////////////////////////////////////////////////////////////
                /** INPUTS TO LISTEN TO */
////////////////////////////////////////////////////////////

/**
 * Configurate elements to be checked -
 * Separate them with commas -
 * Default inputs configuration -
 * @type {String}
 */
export const inputsToListen = 'input, textarea'
/**
 * Configurate elements that should be INTEGERS only -
 * Separate them with commas -
 * Default inputs configuration -
 * @type {String}
 */
export const thisInputShouldBeInt = '#persons, #total_time, #resting_time, #oven_time'
/**
 * Configurate elements that will not receive the check / wrong icons
 * Separate them with commas
 * @type {String}
 */
export const inputsNotToAppend = '#custom_ingredient'
/**
 * Configurate elements that can be Empty -
 * !! ATTENTION !! All elements MUST refer to the attribute name of each inputs -
 * An array of strings separated by commas -
 * Default inputs configuration -
 * You can either change this default settings or
 * force new ones when you construct inside the options object -
 * @type {Array < String >}
 */
export const inputsCanBeEmpty = ['step_3', 'step_4', 'step_5', 'step_6', 'file', 'video_file', 'video_link', 'resting_time']
/**
 * Configurate elements that can contain special chars -
 * !! ATTENTION !! All elements MUST refer to the attribute name of each inputs -
 * An array of strings separated by commas -
 * Default inputs configuration -
 * You can either change this default settings or
 * force new ones when you construct inside the options object -
 * @type {Array < String >}
 */
export const inputsCanContainSpecialChars = ['Mot de Passe', 'Mot de Passe de confirmation', 'Email', 'file', 'video_file']

////////////////////////////////////////////////////////////
                /** INPUTS NOT TO LISTEN TO */
////////////////////////////////////////////////////////////
// TODO
/**
 * Configurate elements to be checked
 * @type {String}
 */
export const defaultInput = 'input, textarea'

////////////////////////////////////////////////////////////
                /** DYNAMIC SECTION TO WATCH */
////////////////////////////////////////////////////////////

/**
 * Defines which area to watch in order to dynamically
 * check newly created inputs values -
 * @type {String}
 */
export const sectionToWatch = '.form-recipe'

////////////////////////////////////////////////////////////
                    /** REGEX */
////////////////////////////////////////////////////////////

/**
 * Configurate which characters are allowed
 * @type {RegExp}
 */
export const allowedSpecialChars = new RegExp('^[\\w\\s,.:;_?\'!\\"*()~&éèêëàâäôöûüùçÀ-]*$')
// export const allowedSpecialChars = new RegExp('^[\\w\\s,.:;_?\'!\\"*()~&éèêëàâäôöûüùçÀ-]+$')
/**
 * Configurate Email : Can only be email@email.co
 * @type {RegExp}
 */
export const emailInputRegex = new RegExp("([a-z0-9A-Z._-]+)@([a-z0-9A-Z_-]+)\\.([a-z\.]{2,6})$")
/**
 * Configurate Username : Can only be : my-user_name99 / myusername99 / my-username99 / my_user_name_99 - no space
 * @type {RegExp}
 */
export const userInputRegex = new RegExp('^[a-zA-Z0-9_-]{1,32}$')
/**
 * Configurate Password and checks that at least :
 * 1 lowercase letter
 * 1 uppercase letter
 * 1 digit
 * 1 special char
 * A minimum of 8 chars
 * A maximum of 128 chars
 * @type {RegExp}
 */
export const strongPasswordInputRegex = new RegExp(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\[\]{};':"\\|,.<>\/?~`]).{8,128}$/)
export const strongPwLowerCaseInputRegex = /(?=.*[a-z])/
export const strongPwUpperCaseInputRegex = /(?=.*[A-Z])/
export const strongPwDigitInputRegex = /(?=.*\d)/
export const strongPwSpecialCharInputRegex = /(?=.*[!@#$%^&*()_+\[\]{};':"\\|,.<>\/?~`])/
export const strongPwLengthInputRegex = /.{8,128}/

////////////////////////////////////////////////////////////
                    /** ALERT */
////////////////////////////////////////////////////////////

/**
 * Configurate alert element class when created with hidden class
 * @type {String}
 */
export const hiddenAlertClass = 'alert-error hidden'
/**
 * Configurate alert element class to target
 * @type {String}
 */
export const alertClass = 'alert-error'
/**
 * Configurate alert element id to target
 * @type {String}
 */
export const alertID = '#alert-error'
/**
 * Configurate input element error class when an error occurs
 * @type {String}
 */
export const inputErrorClass = 'input_error'
/**
 * Configurate input element valid class when input is correctly set
 * @type {String}
 */
export const inputValidClass = 'valid_input'
/**
 * Configurate hidden class name for alert to be removed / added
 * @type {String}
 */
export const hiddenClass = 'hidden'
/**
 * Configurate tooltip class name for tooltip to be shown
 * @type {String}
 */
export const tooltip = '.tooltiptext'

////////////////////////////////////////////////////////////
                    /** END OF CONFIG */
////////////////////////////////////////////////////////////