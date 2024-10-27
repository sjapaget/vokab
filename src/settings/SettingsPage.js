import domReady from '@wordpress/dom-ready';
import { __ } from '@wordpress/i18n';
import { createRoot } from '@wordpress/element';
import { 
    Panel,
    PanelBody,
    PanelRow,
    Button,
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
    __experimentalHeading as Heading,
} from '@wordpress/components';

import { useSettings } from './useSettings';
import WordsPerPracticeSessionControl from './WordsPerPracticeSessionControl';
import PracticeReminderFrequencyControl from './PracticeReminderFrequencyControl';

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
            <Button
                variant='primary'
                onClick={ saveSettings }
                __next40pxDefaultSize
            >
                { __( 'Save Settings', 'vokab' ) }
            </Button>
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
