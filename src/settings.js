import apiFetch from '@wordpress/api-fetch';
import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import { 
    createRoot,
    useState,
    useEffect 
} from '@wordpress/element';
import { 
    Panel,
    PanelBody,
    PanelRow,
    TextareaControl,
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
    __experimentalNumberControl as NumberControl,
    SelectControl,
    Button,
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
    __experimentalHeading as Heading,
} from '@wordpress/components';

const SettingsPage = () => {

    const {
        wordsPerPracticeSession,
        setWordsPerPracticeSession,
        practiceReminderFrequency,
        setPracticeReminderFrequency,
        saveSettings
    } = useSettings();

    return (
        <>
            <Heading level={ 1 } >
                { __( 'Vokab Settings', 'vokab' ) }
            </Heading>
            <Panel>
                <PanelBody
                    title={ __( 'Practice Sessions', 'vokab' ) }
                >
                    <PanelRow>
                        <WordsPerPracticeSessionControl
                            value={ wordsPerPracticeSession }
                            onChange={ ( value ) => setWordsPerPracticeSession( value ) }
                        />
                    </PanelRow>
                    <PanelRow>
                        <PracticeReminderFrequencyControl
                            value={ practiceReminderFrequency }
                            onChange={ setPracticeReminderFrequency }
                        />
                    </PanelRow>
                </PanelBody>
            </Panel>
            <SaveButton onClick={ saveSettings } />
        </>
    );
};

domReady( () => {
    const root = createRoot(
        document.getElementById( 'vokab-settings' )
    );

    if ( root ) {
        root.render( <SettingsPage /> );
    }
} );

const useSettings = () => {
    const [ wordsPerPracticeSession, setWordsPerPracticeSession ] = useState();
    const [ practiceReminderFrequency, setPracticeReminderFrequency ] = useState();

    useEffect( () => {
        apiFetch( { path: 'wp/v2/settings' } ).then( ( settings ) => {
                setWordsPerPracticeSession( settings.vokab.wordsPerPracticeSession );
                setPracticeReminderFrequency( settings.vokab.practiceReminderFrequency );
        } );
    }, [] );

    const saveSettings = () => {
        apiFetch( {
            path: '/wp/v2/settings',
            method: 'POST',
            data: {
                vokab: {
                    wordsPerPracticeSession,
                    practiceReminderFrequency,
                },
            },
        } ).then( (data) => console.log(data) );
    }

    return {
        wordsPerPracticeSession,
        setWordsPerPracticeSession,
        practiceReminderFrequency,
        setPracticeReminderFrequency,
        saveSettings
    };
};

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
            __nextHasNoMarginBottom
        />
    )
}

const SaveButton = ( { onClick } ) => {
    return (
        <Button
            variant='primary'
            onClick={ onClick }
            __next40pxDefaultSize
        >
            { __( 'Save', 'vokab' ) }
        </Button>
    )
}