import { __ } from '@wordpress/i18n';
import { SelectControl } from '@wordpress/components';

const PracticeReminderFrequencyControl = ( { value, onChange } ) => {
    return (
        <SelectControl
            size="__unstable-large"
            label={ __( 'Practice Reminder Frequency', 'vokab' ) }
            labelPosition="side"
            onChange={ onChange }
            options={[
                {
                    disabled: true,
                    label: __( 'Select an option', 'vokab' ),
                    value: ''
                },
                {
                    label: __( 'Daily', 'vokab' ),
                    value: 'daily'
                },
                {
                    label: __( 'Weekly', 'vokab' ),
                    value: 'weekly'
                },
                {
                    label: __( 'Never', 'vokab' ),
                    value: 'never'
                }
            ]}
            value={ value }
            __nextHasNoMarginBottom
        />
    )
}

export default PracticeReminderFrequencyControl;
