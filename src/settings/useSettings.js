import apiFetch from '@wordpress/api-fetch';
import { 
    useState,
    useEffect 
} from '@wordpress/element';

export const useSettings = () => {
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
        } );
    }

    return {
        wordsPerPracticeSession,
        setWordsPerPracticeSession,
        practiceReminderFrequency,
        setPracticeReminderFrequency,
        saveSettings
    };
};
