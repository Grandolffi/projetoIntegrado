import React, { useEffect, useMemo   } from 'react';
import { ActivityIndicator, View, StyleSheet } from 'react-native';
import { logoutUser } from '../../API/Login';
import { useNavigation } from '@react-navigation/native';

// Este é o COMPONENTE React. Ele deve começar com letra MAIÚSCULA.
export const LogoutScreen = () => {  
    const navigation = useNavigation();
    
    const logoutAction = useMemo(() => async () => {
        try {
            await logoutUser(); 
            navigation.replace('Login'); 
        } catch (error) {
            console.error("Erro no logout: ", error);
            navigation.navigate('Login');
        }
    }, [navigation]);

    useEffect(() => {
        // Chame a ação de logout imediatamente
        logoutAction();
    }, [logoutAction]);

    // Retorna um indicador de carregamento enquanto a ação é executada
    return (
        <View style={styles.container}>
            <ActivityIndicator size="large" color="#0A212F" />
        </View>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    }
});
