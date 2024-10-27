import { __ } from '@wordpress/i18n';
import { 
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
    __experimentalNumberControl as NumberControl
} from '@wordpress/components';

const WordsPerPracticeSessionControl = ( { value, onChange } ) => {
    return (
        <NumberControl
            label={ __( 'Words Per Practice Session', 'vokab' ) }
            labelPosition="side"
            onChange={ onChange }
            value= { value }
            __nextHasNoMarginBottom
        />
    );
}

export default WordsPerPracticeSessionControl;
